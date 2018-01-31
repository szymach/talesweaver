<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Doctrine\ItemRepository as DoctrineRepository;
use App\Repository\Interfaces\FindableByIdRepository;
use App\Repository\Traits\ParamConverterRepository;
use App\Repository\Traits\SceneItemRepositoryTrait;
use App\Security\UserProvider;

/**
 * @property DoctrineRepository $doctrineRepository
 * @property UserProvider $userProvider
 */
class ItemRepository implements FindableByIdRepository
{
    use ParamConverterRepository, SceneItemRepositoryTrait;

    public function __construct(
        DoctrineRepository $doctrineRepository,
        UserProvider $userProvider
    ) {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }
}
