<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Publication\ById;
use Talesweaver\Application\Query\Publication\PublicById;
use Talesweaver\Domain\Publication;

final class PublicationResolver
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(QueryBus $queryBus, ResponseFactoryInterface $responseFactory)
    {
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function publicFromRequest(ServerRequestInterface $request): Publication
    {
        $id = $this->getId($request);
        $publication = $this->queryBus->query(new PublicById($id));
        $this->assertPublicationFound($id, $publication);

        if (false === $publication->isVisible()) {
            throw $this->responseFactory->notFound(
                "Publication {$publication->getId()->toString()}) is not visible."
            );
        }

        return $publication;
    }

    public function fromRequest(ServerRequestInterface $request): Publication
    {
        $id = $this->getId($request);
        $publication = $this->queryBus->query(new ById($id));
        $this->assertPublicationFound($id, $publication);

        return $publication;
    }

    private function getId(ServerRequestInterface $request): UuidInterface
    {
        $requestId = $request->getAttribute('id');
        if (false === Uuid::isValid($requestId)) {
            throw $this->responseFactory->notFound("{$requestId} is not a valid UUID.");
        }

        return Uuid::fromString($requestId);
    }

    private function assertPublicationFound(UuidInterface $id, $publication): void
    {
        if (false === $publication instanceof Publication) {
            throw $this->responseFactory->notFound("No publication for id {$id->toString()}.");
        }
    }
}
