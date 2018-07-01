<?php

declare(strict_types=1);

namespace Domain;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Traits\CreatedByTrait;
use Domain\Traits\TimestampableTrait;
use Domain\Traits\TranslatableTrait;
use Ramsey\Uuid\UuidInterface;

class Book
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
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
     * @param string $title
     * @param User $author
     */
    public function __construct(UuidInterface $id, string $title, User $author)
    {
        Assertion::notBlank($title, sprintf(
            'Cannot create a book without a title for author "%s"!',
            $author->getUsername()
        ));

        $this->id = $id;
        $this->title = $title;
        $this->chapters = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @return void
     */
    public function edit(string $title, ?string $description): void
    {
        Assertion::notBlank($title, sprintf(
            'Tried to set an empty title on book with id "%s"!',
            $this->id->toString()
        ));

        $this->title = $title;
        $this->description = $description;

        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getChapters(): Collection
    {
        return $this->chapters;
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