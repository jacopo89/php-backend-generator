<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Provider;


use BackendGenerator\Bundle\Exception\AlreadyDefinedResourceException;
use BackendGenerator\Bundle\Exception\UndefinedResourceException;
use BackendGenerator\Bundle\Repository\ResourceRepositoryInterface;

class ResourceRepositoryProvider
{

    /**
     * @var ResourceRepositoryInterface[]
     */
    private array $resources;

    /**
     * @param iterable $resourcesRepository
     */
    public function __construct(iterable $resourcesRepository)
    {
        foreach ($resourcesRepository as $resource) {
            if (isset($this->resources[$resource->getResourceName()])) {
                throw new AlreadyDefinedResourceException($resource);
            }
            $this->resources[$resource->getResourceName()] = $resource;
        }
    }

    /**
     * @param string $resourcename
     * @return ResourceRepositoryInterface
     */
    public function get(string $resourcename): ResourceRepositoryInterface
    {
        if (!isset($this->resources[$resourcename])) {
            throw new UndefinedResourceException($resourcename);
        }
        return $this->resources[$resourcename];
    }

    /**
     * @return ResourceRepositoryInterface[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

}