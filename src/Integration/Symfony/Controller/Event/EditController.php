<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Event\Edit\Command;
use Talesweaver\Application\Event\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;

class EditController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        UrlGenerator $urlGenerator
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Event $event): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Edit::class, new DTO($event), [
            'action' => $this->urlGenerator->generate(
                'event_edit',
                ['id' => $event->getId()]
            ),
            'eventId' => $event->getId(),
            'model' => SceneEvents::getEventForm(get_class($event->getModel())),
            'scene' => $event->getScene()
        ]);

        if (true === $formHandler->isSubmissionValid()) {
            $this->processFormDataAndRedirect($event, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial/simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'event.header.edit']
            )
        ], true === $formHandler->displayErrors() ? 200 : 400);
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
