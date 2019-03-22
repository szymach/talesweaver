<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Edit\Command;
use Talesweaver\Application\Command\Event\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\EventResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Character\ForScene;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\ValueObject\ShortText;

final class EditController
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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        EventResolver $eventResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        QueryBus $queryBus,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->eventResolver = $eventResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
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
                'characters' => $this->queryBus->query(new ForScene($event->getScene())),
                'eventId' => $event->getId(),
                'scene' => $event->getScene()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($event, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'partial/simpleForm.html.twig',
            ['form' => $formHandler->createView(), 'title' => 'event.header.edit'],
            $formHandler->displayErrors()
        );
    }

    private function processFormDataAndRedirect(Event $event, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command($event, new ShortText($dto->getName()), $dto->getCharacters())
        );

        return $this->responseFactory->success();
    }
}
