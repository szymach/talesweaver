<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Domain\Chapter;

final class ChapterResolver
{

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(ResponseFactoryInterface $responseFactory, QueryBus $queryBus)
    {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Chapter
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No chapter id!');
        }

        $chapter = $this->queryForChapter($id);
        if (false === $chapter instanceof Chapter) {
            throw $this->responseFactory->notFound("No chapter for id \"{$id->toString()}\"!");
        }

        return $chapter;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Chapter
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $chapter = $this->queryForChapter($id);
        if (false === $chapter instanceof Chapter) {
            null;
        }

        return $chapter;
    }

    public function fromQueryFilter(
        ServerRequestInterface $request,
        string $filterKey = 'filter',
        string $idAttribute = 'chapter'
    ): ?Chapter {
        $id = $request->getQueryParams()[$filterKey][$idAttribute] ?? null;
        if (null === $id || false === Uuid::isValid($id)) {
            return null;
        }

        $chapter = $this->queryForChapter(Uuid::fromString($id));
        if (false === $chapter instanceof Chapter) {
            null;
        }

        return $chapter;
    }

    private function queryForChapter(UuidInterface $id): ?Chapter
    {
        return $this->queryBus->query(new ById($id));
    }

    private function getId(ServerRequestInterface $request, string $idAttribute): ?UuidInterface
    {
        $id = $request->getAttribute($idAttribute);
        if (null === $id) {
            return null;
        }

        return Uuid::fromString($id);
    }
}
