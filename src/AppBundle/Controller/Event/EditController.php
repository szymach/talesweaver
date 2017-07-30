<?php

namespace AppBundle\Controller\Event;

use AppBundle\Entity\Event;
use AppBundle\Enum\SceneEvents;
use AppBundle\Event\Edit\Command;
use AppBundle\Event\Edit\DTO;
use AppBundle\Form\Event\EditType;
use AppBundle\JSON\EventParser;
use AppBundle\Templating\Event\FormView;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class EditController
{
    /**
     * @var FormView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EventParser
     */
    private $eventParser;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        EventParser $eventParser,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->eventParser = $eventParser;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Event $event)
    {
        $model = $this->eventParser->parse($event);
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($event->getName(), $model),
            [
                'action' => $this->router->generate(
                    'app_event_edit',
                    ['id' => $event->getId()]
                ),
                'model' => SceneEvents::getEventForm(get_class($model)),
                'scene' => $event->getScene()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($event, $form->getData()));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form);
    }
}
