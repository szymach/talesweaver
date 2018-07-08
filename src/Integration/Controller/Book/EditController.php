<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Book;

use Talesweaver\Integration\Form\Book\EditType;
use Talesweaver\Integration\Routing\RedirectToEdit;
use Talesweaver\Integration\Templating\Book\EditView;
use Talesweaver\Application\Book\Edit\Command;
use Talesweaver\Application\Book\Edit\DTO;
use Talesweaver\Domain\Book;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function __invoke(Request $request, Book $book)
    {
        $dto = new DTO($book);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $book));

            return $this->redirector->createResponse('book_edit', $book->getId());
        }

        return $this->templating->createView($form, $book);
    }
}
