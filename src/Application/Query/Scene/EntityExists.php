<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

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
    private $chapterId;

    public function __construct(string $title, ?UuidInterface $id, ?UuidInterface $chapterId)
    {
        $this->title = $title;
        $this->id = $id;
        $this->chapterId = $chapterId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getChapterId(): ?UuidInterface
    {
        return $this->chapterId;
    }
}
