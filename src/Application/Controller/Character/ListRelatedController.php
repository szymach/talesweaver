<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Query\Character\RelatedPage;

final class ListRelatedController
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
            'scene\common\relatedList.html.twig',
            [
                'list' => $this->queryBus->query(new RelatedPage($scene, $page)),
                'sceneId' => $scene->getId(),
                'sceneTitle' => $scene->getTitle(),
                'type' => 'character'
            ],
            'character.header.related'
        );
    }
}
