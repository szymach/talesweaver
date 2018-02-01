<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use App\Security\UserProvider;
use Doctrine\ORM\EntityRepository;

/**
 * @property EntityRepository $doctrineRepository
 * @property UserProvider $userProvider
 */
trait ParamConverterRepository
{
    public function getClassName(): string
    {
        return $this->doctrineRepository->getClassName();
    }

    public function find(string $id)
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()
        ]);
    }
}
