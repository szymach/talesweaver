<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Book\Edit\Command;
use Talesweaver\Application\Book\Edit\DTO;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Book\EditType;
use Talesweaver\Integration\Symfony\Routing\RedirectToEdit;
use Talesweaver\Integration\Symfony\Templating\Book\EditView;

class EditController
{
    /**
     * @var EditView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RedirectToEdit
     */
    private $redirector;

    public function __construct(
        EditView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
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

        return $this->templating->createView($form, $book);
    }

    private function processFormDataAndRedirect(Book $book, DTO $dto): ResponseInterface
    {
        $description = $dto->getDescription();
        $this->commandBus->handle(new Command(
            $book,
            new ShortText($dto->getTitle()),
            null !== $description ? new LongText($description) : null
        ));

        return $this->redirector->createResponse('book_edit', $book->getId());
    }
}
