<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Location\ById;
use Talesweaver\Domain\Location;

class LocationResolver
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(ResponseFactoryInterface $responseFactory, QueryBus $queryBus)
    {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Location
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No location id!');
        }

        $location = $this->queryForLocation($id);
        if (false === $location instanceof Location) {
            throw $this->responseFactory->notFound("No location for id \"{$id->toString()}\"!");
        }

        return $location;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Location
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $location = $this->queryForLocation($id);
        if (false === $location instanceof Location) {
            null;
        }

        return $location;
    }

    private function queryForLocation(UuidInterface $id): ?Location
    {
        return $this->queryBus->query(new ById($id));
    }

    private function getId(ServerRequestInterface $request, string $idAttribute): ?UuidInterface
    {
        $id = $request->getAttribute($idAttribute);
        if (null === $id) {
            return null;
        }

        return Uuid::fromString($id);
    }
}
