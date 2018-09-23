<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Domain\Item;

class DisplayController
{
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
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        HtmlContent $htmlContent
    ) {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(Item $item): ResponseInterface
    {
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
