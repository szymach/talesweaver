<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Repository\Doctrine\UserRepository as DoctrineRepository;

class UserRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    public function __construct(DoctrineRepository $doctrineRepository)
    {
        $this->doctrineRepository = $doctrineRepository;
    }

    public function findOneByUsername(string $username): ?User
    {
        return $this->doctrineRepository->findOneBy(['username' => $username]);
    }

    public function findOneByActivationToken(string $code): ?User
    {
        return $this->doctrineRepository->findOneByActivationToken($code);
    }
}
