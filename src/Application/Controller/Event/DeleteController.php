<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Event\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Event;

class DeleteController
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
        $this->commandBus->dispatch(
            new Command($this->getEvent($request->getAttribute('id')))
        );

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getEvent(?string $id): Event
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No event id!');
        }

        $uuid = Uuid::fromString($id);
        $event = $this->queryBus->query(new ById($uuid));
        if (false === $event instanceof Event
            || $this->authorContext->getAuthor() !== $event->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No event for id "%s"!', $uuid->toString()));
        }

        return $event;
    }
}
