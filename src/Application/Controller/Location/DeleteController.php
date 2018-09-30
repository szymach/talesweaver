<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Location\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Location\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Location;

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
            new Command($this->getLocation($request->getAttribute('id')))
        );

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getLocation(?string $id): Location
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No location id!');
        }

        $uuid = Uuid::fromString($id);
        $location = $this->queryBus->query(new ById($uuid));
        if (false === $location instanceof Location
            || $this->authorContext->getAuthor() !== $location->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No location for id "%s"!', $uuid->toString()));
        }

        return $location;
    }
}
