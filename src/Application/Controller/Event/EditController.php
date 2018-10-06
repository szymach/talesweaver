<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Edit\Command;
use Talesweaver\Application\Command\Event\Edit\DTO;
use Talesweaver\Application\Form\Event\EventModelResolver;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Edit;
use Talesweaver\Application\Http\Entity\EventResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var EventModelResolver
     */
    private $eventModelResolver;

    /**
     * @var CommandBus
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
        EventResolver $eventResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        EventModelResolver $eventModelResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        UrlGenerator $urlGenerator
    ) {
        $this->eventResolver = $eventResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->eventModelResolver = $eventModelResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $event = $this->eventResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($event),
            [
                'action' => $this->urlGenerator->generate('event_edit', ['id' => $event->getId()]),
                'eventId' => $event->getId(),
                'model' => $this->eventModelResolver->resolve(get_class($event->getModel())),
                'scene' => $event->getScene()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($event, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial/simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'event.header.edit']
            )
        ], false === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processFormDataAndRedirect(Event $event, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $event,
            new ShortText($dto->getName()),
            $dto->getModel()
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
