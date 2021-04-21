<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Provider;


use BackendGenerator\Bundle\BackendGeneratorBundle\Exception\AlreadyDefinedResourceException;
use BackendGenerator\Bundle\BackendGeneratorBundle\Exception\UndefinedResourceException;

class ResourceProvider
{

    /**
     * @var ResourceInterface[]
     */
    private array $resources;

    /**
     * @param iterable $resourcesEntities
     */
    public function __construct(iterable $resourcesEntities)
    {
        foreach ($resourcesEntities as $resource) {
            if (isset($this->resources[$resource->getResourceName()])) {
                throw new AlreadyDefinedResourceException($resource);
            }
            $this->resources[$resource->getResourceName()] = $resource;
        }
    }

    /**
     * @param string $resourcename
     * @return ResourceInterface
     */
    public function get(string $resourcename): ResourceInterface
    {
        if (!isset($this->resources[$resourcename])) {
            throw new UndefinedResourceException($resourcename);
        }
        return $this->resources[$resourcename];
    }

    /**
     * @return ResourceInterface[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

}