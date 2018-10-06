<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Item\ItemsPage;

class ListController
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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        HtmlContent $htmlContent
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene\items\list.html.twig',
                [
                    'items' => $this->queryBus->query(new ItemsPage($scene, $page)),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null,
                    'page' => $page
                ]
            )
        ]);
    }
}
