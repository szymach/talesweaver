<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Create\DTO as CreateDTO;
use Talesweaver\Application\Command\Scene\Edit\DTO as EditDTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;

final class EditController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

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

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        QueryBus $queryBus,
        CommandBus $commandBus
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new EditDTO($scene),
            ['sceneId' => $scene->getId()]
        );

        $isAjax = is_xml_http_request($request);
        if (true === $formHandler->isSubmissionValid()) {
            $response = $this->processFormDataAndRedirect($scene, $formHandler->getData(), $isAjax);
        } elseif (true === $formHandler->displayErrors() && true === $isAjax) {
            $response = $this->apiResponseFactory->form(
                'scene/form/editForm.html.twig',
                ['form' => $formHandler->createView()],
                true
            );
        } else {
            $response = $this->responseFactory->fromTemplate(
                'scene/editForm.html.twig',
                $this->getViewParameters($request, $scene, $formHandler)
            );
        }

        return $response;
    }

    private function processFormDataAndRedirect(Scene $scene, EditDTO $dto, bool $isAjax): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($scene));

        return true === $isAjax
            ? $this->apiResponseFactory->success()
            : $this->responseFactory->redirectToRoute('scene_edit', ['id' => $scene->getId()])
        ;
    }

    private function getViewParameters(
        ServerRequestInterface $request,
        Scene $scene,
        FormHandlerInterface $formHandler
    ): array {
        $parameters = [
            'form' => $formHandler->createView(),
            'sceneId' => $scene->getId(),
            'title' => $scene->getTitle(),
            'characters' => [],
            'items' => [],
            'locations' => [],
            'events' => []
        ];

        if (null !== $scene->getChapter()) {
            $chapter = $scene->getChapter();
            $parameters['chapterTitle'] = $chapter->getTitle();
            $parameters['chapterId'] = $chapter->getId();
            $parameters['relatedScenes'] = $this->queryBus->query(new ScenesPage($chapter, 1));
            $parameters['nextSceneForm'] = $this->createNextSceneForm($request, $chapter);
        } else {
            $parameters['chapterTitle'] = null;
            $parameters['chapterId'] = null;
            $parameters['relatedScenes'] = [];
        }

        return $parameters;
    }

    private function createNextSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new CreateDTO($chapter)
        )->createView();
    }
}
