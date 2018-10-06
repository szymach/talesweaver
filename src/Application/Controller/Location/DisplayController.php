<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\LocationResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Domain\Location;

class DisplayController
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
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        LocationResolver $locationResolver,
        QueryBus $queryBus,
        HtmlContent $htmlContent,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->locationResolver = $locationResolver;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $location = $this->locationResolver->fromRequest($request);
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\locations\display.html.twig',
                [
                    'location' => $location,
                    'timeline' => $this->queryBus->query(
                        new ForEntity($location->getId(), Location::class)
                    )
                ]
            )
        ]);
    }
}
