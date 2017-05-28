<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Create\Command;
use AppBundle\Form\Book\CreateType;
use AppBundle\Routing\Book\RedirectToEdit;
use AppBundle\Templating\Book\CreateView;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateController
{
    /**
     * @var CreateView
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
        CreateView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->redirector = $redirector;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(CreateType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $bookId = Uuid::uuid4();
            $this->commandBus->handle(new Command($bookId, $form->getData()));

            return $this->redirector->createResponse($bookId);
        }

        return $this->templating->createView($form);
    }
}
