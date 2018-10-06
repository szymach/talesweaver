<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ScenesPage;

class RelatedListController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ChapterResolver $chapterResolver,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        QueryBus $queryBus
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->queryBus = $queryBus;
    }

    /**
     * @TODO missing test case
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene/related/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'chapterTitle' => $chapter->getTitle(),
                    'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                    'page' => $page
                ]
            )
        ]);
    }
}
