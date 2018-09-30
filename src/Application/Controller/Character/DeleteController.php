<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Character\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Character;

class DeleteController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        AuthorContext $authorContext,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $character = $this->getCharacter($request->getAttribute('id'));
        $this->commandBus->dispatch(new Command($character));

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getCharacter(?string $id): Character
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No character id!');
        }

        $uuid = Uuid::fromString($id);
        $character = $this->queryBus->query(new ById($uuid));
        if (false === $character instanceof Character
            || $this->authorContext->getAuthor() !== $character->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No character for id "%s"!', $uuid->toString()));
        }

        return $character;
    }
}
