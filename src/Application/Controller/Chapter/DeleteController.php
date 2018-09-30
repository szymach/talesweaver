<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Chapter;

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
        $chapter = $this->getChapter($request->getAttribute('id'));
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId(): null;
        $this->commandBus->dispatch(new Command($chapter));

        if (true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return null !== $bookId
            ? $this->responseFactory->redirectToRoute('book_edit', ['id' => $bookId])
            : $this->responseFactory->redirectToRoute(
                'chapter_list',
                ['page' => $request->getAttribute('page')]
            )
        ;
    }

    private function getChapter(?string $id): Chapter
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No chapter id!');
        }

        $uuid = Uuid::fromString($id);
        $chapter = $this->queryBus->query(new ById($uuid));
        if (false === $chapter instanceof Chapter
            || $this->authorContext->getAuthor() !== $chapter->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No chapter for id "%s"!', $uuid->toString()));
        }

        return $chapter;
    }
}
