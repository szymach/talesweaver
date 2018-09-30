<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Event\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Event;

class DisplayController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

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
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\events\display.html.twig',
                ['event' => $this->getEvent($request->getAttribute('id'))]
            )
        ]);
    }

    private function getEvent(?string $id): Event
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No event id!');
        }

        $uuid = Uuid::fromString($id);
        $event = $this->queryBus->query(new ById($uuid));
        if (false === $event instanceof Event
            || $this->authorContext->getAuthor() !== $event->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No event for id "%s"!', $uuid->toString()));
        }

        return $event;
    }
}
