<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Create\Event;
use AppBundle\Form\Book\CreateType;
use AppBundle\Routing\Book\RedirectToEdit;
use AppBundle\Templating\Book\CreateView;
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
    private $eventBus;

    /**
     * @var RedirectToEdit
     */
    private $redirector;

    public function __construct(
        CreateView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $eventBus,
        RedirectToEdit $redirector
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->eventBus = $eventBus;
        $this->redirector = $redirector;
    }

    public function createAction(Request $request, $page)
    {
        $form = $this->formFactory->create(CreateType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->eventBus->handle(new Event($form->getData()));

            return $this->redirector->createResponse();
        }

        return $this->templating->createView($form, $page);
    }
}
