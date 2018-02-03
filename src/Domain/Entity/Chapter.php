<?php

declare(strict_types=1);

namespace Domain\Entity;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Entity\Traits\CreatedByTrait;
use Domain\Entity\Traits\TimestampableTrait;
use Domain\Entity\Traits\TranslatableTrait;
use Ramsey\Uuid\UuidInterface;

class Chapter
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
     * @var Book
     */
    private $book;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $characters;

    public function __construct(UuidInterface $id, string $title, ?Book $book, User $author)
    {
        Assertion::notBlank($title, sprintf(
            'Cannot create a chapter without a title for author "%s"!',
            (string) $author
        ));

        $this->id = $id;
        $this->title = $title;
        $this->book = $book;

        $this->characters = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return (string) $this->title;
    }

    /**
     * @param string $title
     * @param Book|null $book
     * @return void
     */
    public function edit(string $title, ?Book $book): void
    {
        Assertion::notBlank($title, sprintf(
            'Tried to set an empty title on chapter with id "%s"!',
            (string) $this->id
        ));

        $this->title = $title;
        $this->book = $book;

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

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addCharacter(Character $character): void
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $this->update();
        }
    }

    public function removeCharacter(Character $character): void
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
