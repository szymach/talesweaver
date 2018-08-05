<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Book
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Chapter[]|Collection
     */
    private $chapters;

    /**
     * @param UuidInterface $id
     * @param ShortText $title
     * @param Author $author
     */
    public function __construct(UuidInterface $id, ShortText $title, Author $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->chapters = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return (string) $this->title;
    }

    /**
     * @param ShortText $title
     * @param LongText|null $description
     * @return void
     */
    public function edit(ShortText $title, ?LongText $description): void
    {
        $this->title = $title;
        $this->description = $description;

        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function getChapters(): array
    {
        return $this->chapters->toArray();
    }

    public function addChapter(Chapter $chapter): void
    {
        if (true === $this->chapters->contains($chapter)) {
            return;
        }

        $this->chapters->add($chapter);

        $this->update();
    }

    public function removeChapter(Chapter $chapter): void
    {
        $this->chapters->removeElement($chapter);

        $this->update();
    }
}
