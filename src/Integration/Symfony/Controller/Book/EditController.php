<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Talesweaver\Application\Book\Edit\Command;
use Talesweaver\Application\Book\Edit\DTO;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Book\EditType;
use Talesweaver\Integration\Symfony\Form\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;

class EditController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->responseFactory = $responseFactory;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(ServerRequestInterface $request, Book $book): ResponseInterface
    {
        $dto = new DTO($book);
        $form = $this->formFactory->create(EditType::class, $dto, ['bookId' => $book->getId()]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($book, $dto);
        }

        return $this->responseFactory->renderResponse(
            'book/editForm.html.twig',
            [
                'form' => $form->createView(),
                'chapterForm' => $this->createChapterForm($book),
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

    private function createChapterForm(Book $book): FormView
    {
        return $this->formFactory->create(CreateType::class, new DTO(), [
            'action' => $this->router->generate('chapter_create', ['bookId' => $book->getId()]),
            'title_placeholder' => 'chapter.placeholder.title.book'
        ])->createView();
    }
}
