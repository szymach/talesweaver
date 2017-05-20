<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\Create\DTO;
use AppBundle\Book\Create\Event;
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

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        MessageBus $eventBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->eventBus = $eventBus;
        $this->router = $router;
    }

    public function createAction(Request $request, $page)
    {
        $dto = new DTO();
        $form = $this->formFactory->create(CreateType::class, $dto);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->eventBus->handle(new Event($dto));

            return new RedirectResponse(
                $this->router->generate('app_book_create')
            );
        }

        return $this->templating->renderResponse(
            'book/createForm.html.twig',
            ['form' => $form->createView(), 'page' => $page]
        );
    }
}
