<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Ramsey\Uuid\UuidInterface;

class EntityExists
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var UuidInterface
     */
    private $id;

    public function __construct(?string $title, ?UuidInterface $id)
    {
        $this->title = $title;
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
