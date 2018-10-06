<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Event\Create\Command;
use Talesweaver\Application\Command\Event\Create\DTO;
use Talesweaver\Application\Form\Event\EventModelResolver;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Create;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

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
        SceneResolver $sceneResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        EventModelResolver $eventModelResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        UrlGenerator $urlGenerator
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->eventModelResolver = $eventModelResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
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
}
