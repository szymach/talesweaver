<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Edit\Command;
use AppBundle\Book\Edit\DTO;
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
    private $commandBus;

    public function __construct(
        EditView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Book $book)
    {
        $dto = new DTO($book);
        $form = $this->formFactory->create(EditType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($dto, $book));

            return new RedirectResponse(
                $this->router->generate('app_book_edit', ['id' => $book->getId()])
            );
        }

        return $this->templating->createView($form, $book);
    }
}
