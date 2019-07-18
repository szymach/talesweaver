<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Query\Scene\PublicationsPage;

final class PublicationListController
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
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        SceneResolver $sceneResolver,
        ApiResponseFactoryInterface $responseFactory,
        QueryBus $queryBus
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'partial/publications.html.twig',
            [
                'publications' => $this->queryBus->query(new PublicationsPage($scene, $page)),
                'createRoute' => 'scene_publish',
                'createParameters' => ['id' => $scene->getId()],
                'listRoute' => 'scene_publication_list',
                'listParameters' => ['id' => $scene->getId(), 'page' => $page],
            ]
        );
    }
}
