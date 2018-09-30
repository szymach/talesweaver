<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Book\Edit\Command;
use Talesweaver\Application\Command\Book\Edit\DTO;
use Talesweaver\Application\Command\Chapter;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Book\Edit;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Book\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
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

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->getBook($request->getAttribute('id'));
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($book),
            ['bookId' => $book->getId()]
        );
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($book, $formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'book/editForm.html.twig',
            [
                'form' => $formHandler->createView(),
                'chapterForm' => $this->createChapterForm($request, $book),
                'bookId' => $book->getId(),
                'title' => $book->getTitle()
            ]
        );
    }

    private function processFormDataAndRedirect(Book $book, DTO $dto): ResponseInterface
    {
        $description = $dto->getDescription();
        $this->commandBus->dispatch(new Command(
            $book,
            new ShortText($dto->getTitle()),
            null !== $description ? new LongText($description) : null
        ));

        return $this->responseFactory->redirectToRoute('book_edit', ['id' => $book->getId()]);
    }

    private function createChapterForm(ServerRequestInterface $request, Book $book): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new Chapter\Create\DTO(),
            [
                'action' => $this->urlGenerator->generate('chapter_create', ['bookId' => $book->getId()]),
                'title_placeholder' => 'chapter.placeholder.title.book'
            ]
        )->createView();
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
