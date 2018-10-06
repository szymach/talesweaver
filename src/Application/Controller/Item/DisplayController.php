<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\ItemResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Domain\Item;

class DisplayController
{
    /**
     * @var ItemResolver
     */
    private $itemResolver;

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
        ItemResolver $itemResolver,
        QueryBus $queryBus,
        HtmlContent $htmlContent,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->itemResolver = $itemResolver;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $item = $this->itemResolver->fromRequest($request);
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\items\display.html.twig',
                [
                    'item' => $item,
                    'timeline' => $this->queryBus->query(
                        new ForEntity($item->getId(), Item::class)
                    )
                ]
            )
        ]);
    }
}
