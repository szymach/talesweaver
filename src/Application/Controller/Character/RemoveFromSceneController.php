<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\RemoveFromScene\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class RemoveFromSceneController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $this->getScene($request->getAttribute('scene_id')),
            $this->getCharacter($request->getAttribute('character_id'))
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $uuid = Uuid::fromString($id);
        $scene = $this->queryBus->query(new Query\Scene\ById($uuid));
        if (false === $scene instanceof Scene
            || $this->authorContext->getAuthor() !== $scene->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No scene for id "%s"!', $uuid->toString()));
        }

        return $scene;
    }

    private function getCharacter(?string $id): Character
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No character id!');
        }

        $uuid = Uuid::fromString($id);
        $character = $this->queryBus->query(new Query\Character\ById($uuid));
        if (false === $character instanceof Character
            || $this->authorContext->getAuthor() !== $character->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No character for id "%s"!', $uuid->toString()));
        }

        return $character;
    }
}
