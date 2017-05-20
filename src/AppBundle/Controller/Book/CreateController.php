<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Create\Event;
use AppBundle\Book\Created\EventRecorder;
use AppBundle\Form\Book\CreateType;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class CreateController
{
    /**
     * @var EngineInterface
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
     * @var EventRecorder
     */
    private $recorder;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        MessageBus $eventBus,
        EventRecorder $recorder,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->eventBus = $eventBus;
        $this->recorder = $recorder;
        $this->router = $router;
    }

    public function createAction(Request $request, $page)
    {
        $form = $this->formFactory->create(CreateType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->eventBus->handle(new Event($form->getData()));

            return new RedirectResponse(
                $this->router->generate(
                    'app_book_edit',
                    ['id' => array_values($this->recorder->recordedMessages())[0]->getId()]
                )
            );
        }

        return $this->templating->renderResponse(
            'book/createForm.html.twig',
            ['form' => $form->createView(), 'page' => $page]
        );
    }
}
