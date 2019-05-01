<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Query\Event\CharacterView;

final class DisplayController
{
    /**
     * @var CharacterResolver
     */
    private $characterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        CharacterResolver $characterResolver,
        QueryBus $queryBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->characterResolver = $characterResolver;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $character = $this->characterResolver->fromRequest($request);
        return $this->responseFactory->display(
            'scene\common\display.html.twig',
            [
                'name' => $character->getName(),
                'description' => $character->getDescription(),
                'avatar' => $character->getAvatar(),
                'events' => $this->queryBus->query(new CharacterView($character))
            ]
        );
    }
}
