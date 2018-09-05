<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Event\Edit\Command;
use Talesweaver\Application\Event\Edit\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\ValueObject\ShortText;
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
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Event $event): ResponseInterface
    {
        $form = $this->formFactory->create(EditType::class, new DTO($event), [
            'action' => $this->responseFactory->generate(
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

    private function processFormDataAndRedirect(Event $event, DTO $dto): ResponseInterface
    {
        $this->commandBus->handle(new Command(
            $event,
            new ShortText($dto->getName()),
            $dto->getModel()
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
