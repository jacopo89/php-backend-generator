<?php
namespace App\Doctrine;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager as DefaultRefreshTokenManager;

class RefreshTokenManager extends DefaultRefreshTokenManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var RefreshTokenRepository
     */
    protected $repository;

    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }
}