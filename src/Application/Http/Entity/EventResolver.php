<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Event\ById;
use Talesweaver\Domain\Event;

class EventResolver
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

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Event
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No event id!');
        }

        $event = $this->queryForEvent($id);
        if (false === $event instanceof Event) {
            throw $this->responseFactory->notFound("No event for id \"{$id->toString()}\"!");
        }

        return $event;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Event
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $event = $this->queryForEvent($id);
        if (false === $event instanceof Event) {
            null;
        }

        return $event;
    }

    private function queryForEvent(UuidInterface $id): ?Event
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
