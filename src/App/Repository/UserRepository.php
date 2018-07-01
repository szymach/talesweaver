<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Repository\UserRepository as DoctrineRepository;
use Domain\Entity\User;

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
