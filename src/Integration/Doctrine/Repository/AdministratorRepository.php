<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\Administrators;
use Talesweaver\Domain\ValueObject\Email;

final class AdministratorRepository extends ServiceEntityRepository implements Administrators
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrator::class);
    }

    public function add(Administrator $administrator): void
    {
        $this->getEntityManager()->persist($administrator);
    }

    public function remove(Administrator $administrator): void
    {
        $this->getEntityManager()->remove($administrator);
    }

    public function findByEmail(Email $email): ?Administrator
    {
        return $this->createQueryBuilder('a')
            ->where('a.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
