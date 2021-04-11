<?php

declare(strict_types=1);

namespace App\Resources\Listing\Repository;

use App\Resources\Listing\Model\ResourceListingCollection;

interface ListingRepositoryInterface
{
    public function getResourceName(): string;
    public function getListing(string $searchTerm = null): ResourceListingCollection;
}