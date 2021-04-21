<?php

declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Repository;

use BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Model\ResourceListingCollection;

interface ListingRepositoryInterface
{
    public function getResourceName(): string;
    public function getListing(string $searchTerm = null): ResourceListingCollection;
}