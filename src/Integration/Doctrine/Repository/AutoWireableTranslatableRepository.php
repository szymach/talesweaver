<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Exception;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;

abstract class AutoWireableTranslatableRepository extends TranslatableRepository implements
    ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);
        if (false === $manager instanceof EntityManager) {
            throw new Exception();
        }

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
