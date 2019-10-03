<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Query\Scene\SceneSummary;

final class SummaryController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    /**
     * @var SceneResolver
     */
    private $sceneResovler;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        ApiResponseFactoryInterface $apiResponseFactory,
        SceneResolver $sceneResovler,
        QueryBus $queryBus,
        HtmlContent $htmlContent
    ) {
        $this->apiResponseFactory = $apiResponseFactory;
        $this->sceneResovler = $sceneResovler;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResovler->fromRequest($request);
        return $this->apiResponseFactory->success([
            'display' => $this->htmlContent->fromTemplate(
                'scene/summary.html.twig',
                [
                    'title' => $scene->getTitle(),
                    'lists' => $this->queryBus->query(new SceneSummary($scene))
                ]
            )
        ]);
    }
}
