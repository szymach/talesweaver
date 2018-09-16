<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Book\Edit\Command;
use Talesweaver\Application\Book\Edit\DTO;
use Talesweaver\Application\Chapter;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Book\Edit;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        MessageBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Book $book): ResponseInterface
    {
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
        $this->commandBus->handle(new Command(
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
}
