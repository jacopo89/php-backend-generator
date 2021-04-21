<?php

declare(strict_types=1);

namespace BackendGenerator\Bundle\Resources\Listing\Repository;

use BackendGenerator\Bundle\Resources\Listing\Model\ResourceListingCollection;

interface ListingRepositoryInterface
{
    public function getResourceName(): string;
    public function getListing(string $searchTerm = null): ResourceListingCollection;
}