<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Event\Edit\Command;
use Talesweaver\Application\Event\Edit\DTO;
use Talesweaver\Domain\Event;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;
use Talesweaver\Integration\Symfony\Form\Event\EditType;
use Talesweaver\Integration\Symfony\Templating\Event\FormView;

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
        $form = $this->formFactory->create(EditType::class, new DTO($event), [
            'action' => $this->router->generate(
                'event_edit',
                ['id' => $event->getId()]
            ),
            'eventId' => $event->getId(),
            'model' => SceneEvents::getEventForm(get_class($event->getModel())),
            'scene' => $event->getScene()
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->processFormDataAndRedirect($event, $form->getData());
        }

        return $this->templating->createView($form, 'event.header.edit');
    }

    private function processFormDataAndRedirect(Event $event, DTO $dto): Response
    {
        $this->commandBus->handle(new Command($event, new ShortText($dto->getName()), $dto->getModel()));

        return new JsonResponse(['success' => true]);
    }
}
