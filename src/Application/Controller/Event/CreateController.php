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
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Event\Create;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Character\ForScene;
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
        SceneResolver $sceneResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        QueryBus $queryBus,
        CommandBus $commandBus,
        ApiResponseFactoryInterface $responseFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
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
                'action' => $this->urlGenerator->generate('event_add', ['id' => $scene->getId()]),
                'characters' => $this->queryBus->query(new ForScene($scene)),
                'scene' => $scene
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($scene, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'partial/simpleForm.html.twig',
            ['form' => $formHandler->createView(), 'title' => 'event.header.new'],
            $formHandler->displayErrors()
        );
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command(Uuid::uuid4(), $scene, new ShortText($dto->getName()))
        );

        return $this->responseFactory->success();
    }
}
