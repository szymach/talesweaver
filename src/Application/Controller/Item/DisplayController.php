<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Item\ById;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Item;

class DisplayController
{
    /**
     * @var AuthorContext
     */
    private $authorContext;

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
        QueryBus $queryBus,
        AuthorContext $authorContext,
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $item = $this->getItem($request->getAttribute('id'));
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

    private function getItem(?string $id): Item
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No item id!');
        }

        $uuid = Uuid::fromString($id);
        $item = $this->queryBus->query(new ById($uuid));
        if (false === $item instanceof Item
            || $this->authorContext->getAuthor() !== $item->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No item for id "%s"!', $uuid->toString()));
        }

        return $item;
    }
}
