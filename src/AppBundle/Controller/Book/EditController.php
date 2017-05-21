<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Edit\DTO;
use AppBundle\Book\Edit\Event;
use AppBundle\Entity\Book;
use AppBundle\Form\Book\EditType;
use AppBundle\Templating\Book\EditView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

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
    private $eventBus;

    public function __construct(
        EditView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $eventBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->eventBus = $eventBus;
        $this->router = $router;
    }

    public function editAction(Request $request, Book $book, $page)
    {
        $dto = new DTO($book);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->eventBus->handle(new Event($dto, $book));

            return new RedirectResponse(
                $this->router->generate('app_book_edit', ['id' => $book->getId()])
            );
        }

        return $this->templating->createView($form, $book, $page);
    }
}
