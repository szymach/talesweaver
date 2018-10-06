<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Event\EventsPage;

class ListController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        SceneResolver $sceneResolver,
        QueryBus $queryBus,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene\events\list.html.twig',
                [
                    'events' => $this->queryBus->query(new EventsPage($scene, $page)),
                    'sceneId' => $scene->getId(),
                    'page' => $page
                ]
            )
        ]);
    }
}
