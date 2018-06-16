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

class Scene
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
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var Character[]|Collection
     */
    private $characters;

    /**
     * @var Item[]|Collection
     */
    private $items;

    /**
     * @var Location[]|Collection
     */
    private $locations;

    /**
     * @param UuidInterface $id
     * @param string $title
     * @param Chapter|null $chapter
     * @param User $author
     */
    public function __construct(UuidInterface $id, string $title, ?Chapter $chapter, User $author)
    {
        Assertion::notBlank($title, sprintf(
            'Cannot create a scene without a title for author "%s"!',
            $author->getUsername()
        ));

        $this->id = $id;
        $this->title = $title;
        $this->chapter = $chapter;

        $this->characters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title ?? '';
    }

    /**
     * @param string $title
     * @param string|null $text
     * @param Chapter|null $chapter
     * @return void
     */
    public function edit(string $title, ?string $text, ?Chapter $chapter): void
    {
        Assertion::notBlank($title, sprintf(
            'Tried to set an empty title on scene with id "%s"!',
            $this->id->toString()
        ));

        $this->title = $title;
        $this->text = $text;
        $this->chapter = $chapter;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getBook(): ?Book
    {
        return null !== $this->chapter && $this->chapter->getBook() ? $this->chapter->getBook() : null;
    }

    public function addCharacter(Character $character): void
    {
        if (true === $this->characters->contains($character)) {
            return;
        }

        $this->characters[] = $character;

        $this->update();
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

    public function addLocation(Location $location): void
    {
        if (true === $this->locations->contains($location)) {
            return;
        }

        $this->locations[] = $location;

        $this->update();
    }

    public function removeLocation(Location $location): void
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addItem(Item $item): void
    {
        if (true === $this->items->contains($item)) {
            return;
        }

        $this->items[] = $item;

        $this->update();
    }

    public function removeItem(Item $item): void
    {
        $this->items->removeElement($item);
        $this->update();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}
