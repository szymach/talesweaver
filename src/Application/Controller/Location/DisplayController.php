<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Location\ById;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Location;

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
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        AuthorContext $authorContext,
        HtmlContent $htmlContent
    ) {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $location = $this->getLocation($request->getAttribute('id'));
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

    private function getLocation(?string $id): Location
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No location id!');
        }

        $uuid = Uuid::fromString($id);
        $location = $this->queryBus->query(new ById($uuid));
        if (false === $location instanceof Location
            || $this->authorContext->getAuthor() !== $location->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No location for id "%s"!', $uuid->toString()));
        }

        return $location;
    }
}
