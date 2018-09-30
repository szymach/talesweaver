<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Book\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Book\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;

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
            new Command($this->getBook($request->getAttribute('id')))
        );

        return $this->responseFactory->redirectToRoute(
            'book_list',
            ['page' => $request->getAttribute('page')]
        );
    }

    private function getBook(?string $id): Book
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No book id!');
        }

        $uuid = Uuid::fromString($id);
        $book = $this->queryBus->query(new ById($uuid));
        if (false === $book instanceof Book
            || $this->authorContext->getAuthor() !== $book->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No book for id "%s"!', $uuid->toString()));
        }

        return $book;
    }
}
