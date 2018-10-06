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
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

class ScenesListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

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
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ChapterResolver $chapterResolver,
        QueryBus $queryBus,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        UrlGenerator $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->chapterResolver = $chapterResolver;
        $this->queryBus = $queryBus;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $router;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
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
