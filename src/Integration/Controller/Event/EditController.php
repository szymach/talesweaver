<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Event;

use Talesweaver\Integration\Enum\SceneEvents;
use Talesweaver\Integration\Form\Event\EditType;
use Talesweaver\Integration\Templating\Event\FormView;
use Talesweaver\Application\Event\Edit\Command;
use Talesweaver\Application\Event\Edit\DTO;
use Talesweaver\Domain\Event;
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
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request, Event $event)
    {
        $form = $this->formFactory->create(
            EditType::class,
            new DTO($event),
            [
                'action' => $this->router->generate(
                    'event_edit',
                    ['id' => $event->getId()]
                ),
                'model' => SceneEvents::getEventForm(get_class($event->getModel())),
                'scene' => $event->getScene()
            ]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->commandBus->handle(new Command($event, $form->getData()));

            return new JsonResponse(['success' => true]);
        }

        return $this->templating->createView($form, 'event.header.edit');
    }
}
