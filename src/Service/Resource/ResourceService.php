<?php
declare(strict_types=1);

namespace App\Service\Resource;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Annotation\ResourceProperty;
use App\Model\JsonData;
use App\Provider\ResourceInterface;
use App\Provider\ResourceProvider;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\Collection;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\Constraint;

class ResourceService
{
    private Reader $annotationReader;
    private ResourceProvider $resourceProvider;
    private const FILE_ENTITY = "App\Entity\File";

    public function __construct(Reader $reader, ResourceProvider $resourceProvider)
    {
        $this->annotationReader = $reader;
        $this->resourceProvider = $resourceProvider;
    }


    public function resourceAnalyzer(ResourceInterface $resource, ResourceInterface $inheritedResource = null): array
    {

        $serializedResource =  ($inheritedResource === null) ? $resource: $inheritedResource;
        $resourceClass = new ReflectionClass($resource);
        $properties = $resourceClass->getProperties();

        $response = [];

        if($inheritedResource === null){
            $filters = $this->getFilters($resourceClass, $properties);
            if(!empty($filters)){
                $response[JsonDATA::FILTERS] = $filters;
            }
        }

        $model = [];

        foreach ($properties as $property) {
            $propertyName = $this->getPropertyName($property);
            if(!$this->checkIfPropertyEligibleForSerialization($property, $serializedResource)) continue;
            $model[$propertyName] = $this->getPropertyModel($property, $serializedResource);
        }

        $response[JsonData::MODEL] = $model;
        $response[JsonData::TITLE] = $resource->getResourceTitle();
        $response[JsonData::RESOURCE_NAME] = $resource->getResourceName();

        return $response;
    }

    private function getResourcePropertyAnnotation(ReflectionProperty $property){

        return $this->annotationReader->getPropertyAnnotation($property, ResourceProperty::class);
    }

    private function getPropertyModel(ReflectionProperty $property, ResourceInterface $resource): array
    {

        $json = [];
        $annotationClass = ResourceProperty::class;
        $annotation = $this->annotationReader->getPropertyAnnotation($property, $annotationClass);
        $validation = $this->annotationReader->getPropertyAnnotation($property, Constraint::class);
        if ($annotationClass === ResourceProperty::class) {
            if (!is_null($annotation)) {
                $json = $this->handleAnnotation($annotation, $json, $resource,$property, $validation);
            }
        }

        $json[JsonData::ID] = $this->getPropertyName($property);
        $json[JsonData::LABEL] = $this->getPropertyName($property);

        return $json;


    }

    private function handleAnnotation(ResourceProperty $annotation, $json, $resource, $property, $validation){

        $json = $annotation->toJson();

        $type = $annotation->type;
        $json = $this->setWriteRead($property,$resource, $json, $type);
        switch($annotation->type){
            case "embedded_single":
            case "embedded_multiple":{
                $resourceName = $this->getResourceStaticElements($annotation->targetClass)["resourceName"];
                $embeddedResource = $this->resourceProvider->get($resourceName);
                $json[JsonData::RESOURCE] = $this->resourceAnalyzer($embeddedResource, $resource);
                $json[JsonData::RESOURCE_NAME] = $resourceName;
            }break;

            case "reference":{
                $resourceName = $this->getResourceStaticElements($annotation->targetClass)["resourceName"];
                $optionText = $this->getResourceStaticElements($annotation->targetClass)["optionText"];
                $json[JsonData::RESOURCE_NAME] = $resourceName;
                $json[JsonData::OPTIONTEXT] = $optionText;

            }break;
            case "enum":{
                $json = $this->handleEnumAnnotation($annotation, $json, $property);
            }break;
            default: {
            }break;
        }
        if($annotation->nullable === false){
            $json[JsonData::VALIDATORS][] = "required";
            $json[JsonData::ERROR_MESSAGES][] = "This field is required";
        }
        return $json;


    }

    private function getResourceStaticElements($resourceClassName): array
    {
        $resourceClass = new ReflectionClass($resourceClassName);
        try {
            return [
                "resourceName" => $resourceClass->getMethod("getResourceName")->invoke(null),
                "optionText" => $resourceClass->getMethod("getDefaultOptionText")->invoke(null)
            ];
        } catch (\ReflectionException $e) {
            dd($resourceClass, $resourceClassName);
        }
    }

    private function handleValidation($validation, $json){

        switch(get_class($validation)){
            case \Symfony\Component\Validator\Constraints\All::class:{
                foreach($validation->constraints as $constraint){
                    $json = $this->handleValidation($constraint, $json);
                }
                return $json;
            }
            default: return $json;
        }


    }

    private function handleEnumAnnotation($annotation, $json, $property): ?array
    {
        if(!$annotation) return null;

        if(isset($annotation->targetClass)){
            $targetClass = $annotation->targetClass;
        }else{
            dd($property);
        }

        $class = new ReflectionClass($targetClass);
        $variable = $annotation->optionsName;
        $options = $class->getConstant($variable);
        $newOptions = [];
        foreach ($options as $key => $value){
            $newOption[JsonData::ID] = $key;
            $newOption[JsonData::LABEL] = $value;
            $newOptions[] = $newOption;
        }
        $json[JsonData::OPTIONS] = $newOptions;
        $json[JsonData::TYPE] = JsonData::ENUM;
        $json[JsonData::CARDINALITY] = 1;

        return $json;

    }


    private function getFilters(ReflectionClass $resourceClass, array $properties): array
    {
        $annotations = $this->annotationReader->getClassAnnotations($resourceClass);

        $filters = [];
        foreach ($annotations as $annotation){
            if(! $annotation instanceof \ApiPlatform\Core\Annotation\ApiFilter) continue;

            switch ($annotation->filterClass){
                case SearchFilter::class: {
                    foreach ($annotation->properties as $propName => $value){
                        $exploded = explode(".",$propName);
                        $comparison = array_pop($exploded);
                        $filteredProperties = array_filter($properties, function (ReflectionProperty $property) use($comparison) { return $property->getName() === $comparison; });
                        $property = array_pop($filteredProperties);
                        if($property){
                            $annotation = $this->getResourcePropertyAnnotation($property);
                            if($annotation instanceof ResourceProperty){
                                if($annotation->type === "string"){
                                    $filters["text"][] = $propName;
                                }else if($annotation->type === "enum"){
                                    $filters["enum"][] = $propName;
                                }else{
                                    $filters["text"][] = $propName;
                                }
                            }
                        }else{
                            $filters["text"][] = $propName;
                        }
                    }
                }
                    break;
                case BooleanFilter::class:{
                    foreach ($annotation->properties as $propName => $value){
                        $filters["boolean"][] = $value;
                    }
                }
            }
        }

        return $filters;
    }



    private function checkIfPropertyEligibleForSerialization(ReflectionProperty $property, ResourceInterface $resource): bool
    {
        $resourceClass = new ReflectionClass($resource);
        $resourceName = strtolower($resourceClass->getShortName());
        $resourceDenormalizationGroup = $resourceName.":write";
        $resourceNormalizationGroup = $resourceName.":read";
        $fileNormalizationGroup = "file:read";
        $fileDenormalizationGroup = "file:write";
        $workflowNormalizationGroup = "workflow:read";
        $workflowDenormalizationGroup = "workflow:write";

        $groupClass = $this->annotationReader->getPropertyAnnotation($property, \Symfony\Component\Serializer\Annotation\Groups::class );

        if($groupClass === null) return false;

        $groups = $groupClass->getGroups();
        $groups = array_map(function($item){ return strtolower($item);}, $groups);

        $isWritable = (in_array($resourceDenormalizationGroup, $groups) || in_array($fileDenormalizationGroup, $groups)||in_array($workflowDenormalizationGroup, $groups));
        $isReadable = (in_array($resourceNormalizationGroup, $groups) || in_array($fileNormalizationGroup, $groups)|| in_array($workflowNormalizationGroup, $groups));

        return $isReadable || $isWritable;
    }


    private function getPropertyName(ReflectionProperty $property): string
    {
        $serializedAnnotation = $this->annotationReader->getPropertyAnnotation($property,  \Symfony\Component\Serializer\Annotation\SerializedName::class );
        if($serializedAnnotation)
            return $serializedAnnotation->getSerializedName();
        return $property->getName();
    }


    private function setWriteRead(ReflectionProperty $property, ResourceInterface $resource, $json, $type)
    {
        if($type==="id"){
            $json[JsonData::WRITE] = false;
            $json[JsonData::READ] = false;
            return $json;
        }

        $resourceClass = new ReflectionClass($resource);
        $resourceName = strtolower($resourceClass->getShortName());
        $resourceDenormalizationGroup = $resourceName.":write";
        $resourceNormalizationGroup = $resourceName.":read";
        $fileNormalizationGroup = "file:read";
        $fileDenormalizationGroup = "file:write";
        $workflowNormalizationGroup = "workflow:read";
        $workflowDenormalizationGroup = "workflow:write";

        $groupClass = $this->annotationReader->getPropertyAnnotation($property, \Symfony\Component\Serializer\Annotation\Groups::class );
        $groups = $groupClass->getGroups();
        $groups = array_map(function($item){ return strtolower($item);}, $groups);

        $isWritable = (in_array($resourceDenormalizationGroup, $groups) || in_array($fileDenormalizationGroup, $groups)||in_array($workflowDenormalizationGroup, $groups));
        $isReadable = (in_array($resourceNormalizationGroup, $groups) || in_array($fileNormalizationGroup, $groups)|| in_array($workflowNormalizationGroup, $groups));

        $json[JsonData::WRITE] = $isWritable;
        $json[JsonData::READ] = $isReadable;

        return $json;


    }

}
