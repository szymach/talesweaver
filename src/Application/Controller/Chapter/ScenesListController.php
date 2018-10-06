<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

class ScenesListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $router;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        UrlGenerator $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->getChapter($request->getAttribute('id'));
        $page = $request->getAttribute('page');
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'sceneForm' => $this->createSceneForm($request, $chapter),
                    'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                    'page' => $page
                ]
            )
        ]);
    }

    private function getChapter(?string $id): Chapter
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No id for scene');
        }

        $chapter = $this->queryBus(new ById());
        if (false === $chapter instanceof Chapter) {
            throw $this->responseFactory->notFound("No scene for id \"{$id}\"");
        }

        return $chapter;
    }

    private function createSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($chapter),
            ['action' => $this->router->generate('scene_create')]
        )->createView();
    }
}
