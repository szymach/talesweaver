<?php

declare(strict_types=1);

namespace Talesweaver\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;

abstract class TranslatableServiceRepository extends TranslatableRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
