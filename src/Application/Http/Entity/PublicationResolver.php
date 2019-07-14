<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Publication\ById;
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

    public function fromRequest(ServerRequestInterface $request): Publication
    {
        $requestId = $request->getAttribute('id');
        if (false === Uuid::isValid($requestId)) {
            throw $this->responseFactory->notFound("{$requestId} is not a valid UUID.");
        }

        $publication = $this->queryBus->query(
            new ById(Uuid::fromString($requestId))
        );

        if (false === $publication instanceof Publication) {
            throw $this->responseFactory->notFound("No publication for id {$requestId}.");
        }

        return $publication;
    }
}
