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
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

class ScenesListController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

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
    private $urlGenerator;

    public function __construct(
        ChapterResolver $chapterResolver,
        QueryBus $queryBus,
        ApiResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        UrlGenerator $router
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $router;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'chapter/scenes/list.html.twig',
            [
                'chapterId' => $chapter->getId(),
                'sceneForm' => $this->createSceneForm($request, $chapter),
                'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                'page' => $page
            ]
        );
    }

    private function createSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($chapter),
            ['action' => $this->urlGenerator->generate('scene_create')]
        )->createView();
    }
}
