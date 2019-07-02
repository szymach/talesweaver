<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Character\Create;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Scene;

final class CreateController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        SceneResolver $sceneResolver,
        ApiResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($scene),
            [
                'action' => $this->urlGenerator->generate('character_new', ['id' => $scene->getId()]),
                'scene' => $scene
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($scene, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'form\modalContent.html.twig',
            ['form' => $formHandler->createView()],
            $formHandler->displayErrors(),
            'character.header.new'
        );
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($scene, Uuid::uuid4()));

        return $this->responseFactory->success();
    }
}
