<?php

declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing;

use BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Exception\UndefinedListingRepositoryException;
use BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Model\ResourceListingCollection;

class Listing
{
    private ResourceRepositoryProvider $provider;

    public function __construct(ResourceRepositoryProvider $provider)
    {
        $this->provider = $provider;
    }

    public function getListing(string $resourceName, string $searchTerm = null): ?ResourceListingCollection
    {
        try {
            $repository = $this->provider->get($resourceName);

            return $repository->getListing($searchTerm);
        } catch (UndefinedListingRepositoryException $e) {

        }

        return null;
    }
}