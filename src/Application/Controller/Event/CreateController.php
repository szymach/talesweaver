<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Event\Create\Command;
use Talesweaver\Application\Command\Event\Create\DTO;
use Talesweaver\Application\Form\Event\EventModelResolver;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

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
        QueryBus $queryBus,
        AuthorContext $authorContext,
        FormHandlerFactoryInterface $formHandlerFactory,
        EventModelResolver $eventModelResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        UrlGenerator $urlGenerator
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->eventModelResolver = $eventModelResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->getScene($request->getAttribute('id'));
        $model = $request->getAttribute('model');
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($scene),
            [
                'scene' => $scene,
                'model' => $this->eventModelResolver->resolve($model),
                'action' => $this->urlGenerator->generate(
                    'event_add',
                    ['id' => $scene->getId(), 'model' => $model]
                )
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($scene, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial/simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'event.header.new']
            )
        ], false === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(
            new Command($id, $scene, new ShortText($dto->getName()), $dto->getModel())
        );

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $uuid = Uuid::fromString($id);
        $scene = $this->queryBus->query(new ById($uuid));
        if (false === $scene instanceof Scene
            || $this->authorContext->getAuthor() !== $scene->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No scene for id "%s"!', $uuid->toString()));
        }

        return $scene;
    }
}
