<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\LocationResolver;
use Talesweaver\Application\Query\Event\LocationView;

final class DisplayController
{
    /**
     * @var LocationResolver
     */
    private $locationResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        LocationResolver $locationResolver,
        QueryBus $queryBus,
        ApiResponseFactoryInterface $responseFactory
    ) {
        $this->locationResolver = $locationResolver;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $location = $this->locationResolver->fromRequest($request);
        return $this->responseFactory->display(
            'scene\common\display.html.twig',
            [
                'name' => $location->getName(),
                'description' => $location->getDescription(),
                'avatar' => $location->getAvatar(),
                'events' => $this->queryBus->query(new LocationView($location))
            ]
        );
    }
}
