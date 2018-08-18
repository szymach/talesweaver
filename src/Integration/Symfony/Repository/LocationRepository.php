<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\DoctrineRepository\LocationRepository as DoctrineRepository;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Symfony\Repository\Provider\UserProvider;

class LocationRepository implements Locations, RequestSecuredRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(DoctrineRepository $doctrineRepository, UserProvider $userProvider)
    {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }

    public function getClassName(): string
    {
        return $this->doctrineRepository->getClassName();
    }

    public function find(UuidInterface $id): ?Location
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function add(Location $location): void
    {
        $this->doctrineRepository->persist($location);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository
            ->createQueryBuilder('l')
            ->delete()
            ->where('l.id = :id')
            ->getQuery()
            ->execute(['id' => $id->toString()])
        ;
    }

    public function findForScene(Scene $scene): array
    {
        return $this->doctrineRepository->findForAuthorAndScene(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $scene
        );
    }

    public function findRelated(Scene $scene): array
    {
        return $this->doctrineRepository->findRelatedToScene(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $scene
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $parameters,
            $id
        );
    }
}
