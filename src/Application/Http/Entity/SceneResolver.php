<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Domain\Scene;

class SceneResolver
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

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Scene
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $scene = $this->queryForScene($id);
        if (false === $scene instanceof Scene) {
            throw $this->responseFactory->notFound("No scene for id \"{$id->toString()}\"!");
        }

        return $scene;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Scene
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $scene = $this->queryForScene($id);
        if (false === $scene instanceof Scene) {
            null;
        }

        return $scene;
    }

    private function queryForScene(UuidInterface $id): ?Scene
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
