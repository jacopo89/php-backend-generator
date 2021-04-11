<?php

declare(strict_types=1);

namespace App\Resources\Listing\Repository\Factory;

use App\Provider\ResourceInterface;
use App\Resources\Listing\Repository\GenericListingRepository;
use Doctrine\Persistence\ManagerRegistry;

class GenericListingRepositoryFactory
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
       $this->registry = $registry;
    }

    public function create(ResourceInterface $resource): GenericListingRepository
    {
        return new GenericListingRepository($this->registry, get_class($resource));
    }
}