<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Character\ById;
use Talesweaver\Domain\Character;

class CharacterResolver
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

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Character
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No character id!');
        }

        $character = $this->queryForCharacter($id);
        if (false === $character instanceof Character) {
            throw $this->responseFactory->notFound("No character for id \"{$id->toString()}\"!");
        }

        return $character;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Character
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $character = $this->queryForCharacter($id);
        if (false === $character instanceof Character) {
            null;
        }

        return $character;
    }

    private function queryForCharacter(UuidInterface $id): ?Character
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
