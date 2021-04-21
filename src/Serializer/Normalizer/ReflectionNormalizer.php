<?php

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Serializer\Normalizer;

use BackendGenerator\Bundle\BackendGeneratorBundle\DTO\Contract\ContractOutput;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ReflectionNormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if(is_object($data)){
            $objectClass = new \ReflectionClass($data);
            $properties = $objectClass->getProperties();
            $array = [];
            foreach ($properties as $property){
                $property->setAccessible(true);
                $array[$property->getName()] = $property->getValue($data);
            }
        }else{
            $array = $data;
        }

        return $this->normalizer->denormalize($array, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return ContractOutput::class == $type;
    }
}
