<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Ramsey\Uuid\UuidInterface;

final class EntityExists
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var UuidInterface|null
     */
    private $id;

    /**
     * @var UuidInterface|null
     */
    private $bookId;

    public function __construct(string $title, ?UuidInterface $id, ?UuidInterface $bookId)
    {
        $this->title = $title;
        $this->id = $id;
        $this->bookId = $bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getBookId(): ?UuidInterface
    {
        return $this->bookId;
    }
}
