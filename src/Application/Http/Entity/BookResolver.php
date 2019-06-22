<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\FilterSet;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Book\ById;
use Talesweaver\Domain\Book;

final class BookResolver
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

    public function fromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): Book
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            throw $this->responseFactory->notFound('No book id!');
        }

        $book = $this->queryForBook($id);
        if (false === $book instanceof Book) {
            throw $this->responseFactory->notFound("No book for id \"{$id->toString()}\"!");
        }

        return $book;
    }

    public function nullableFromRequest(ServerRequestInterface $request, string $idAttribute = 'id'): ?Book
    {
        $id = $this->getId($request, $idAttribute);
        if (null === $id) {
            return null;
        }

        $book = $this->queryForBook($id);
        if (false === $book instanceof Book) {
            null;
        }

        return $book;
    }

    public function fromQueryFilter(
        ServerRequestInterface $request,
        string $filterKey = FilterSet::QUERY_KEY,
        string $idAttribute = 'book'
    ): ?Book {
        $id = $request->getQueryParams()[$filterKey][$idAttribute] ?? null;
        if (null === $id || false === Uuid::isValid($id)) {
            return null;
        }

        $book = $this->queryForBook(Uuid::fromString($id));
        if (false === $book instanceof Book) {
            null;
        }

        return $book;
    }

    private function queryForBook(UuidInterface $id): ?Book
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
