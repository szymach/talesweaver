<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Item\ById;
use Talesweaver\Domain\Item;

class ItemResolver
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

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Item
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No item id!');
        }

        $item = $this->queryForItem($id);
        if (false === $item instanceof Item) {
            throw $this->responseFactory->notFound("No item for id \"{$id->toString()}\"!");
        }

        return $item;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Item
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $item = $this->queryForItem($id);
        if (false === $item instanceof Item) {
            null;
        }

        return $item;
    }

    private function queryForItem(UuidInterface $id): ?Item
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
