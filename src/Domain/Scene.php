<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Scene
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
     * @var LongText|null
     */
    private $text;

    /**
     * @var Chapter|null
     */
    private $chapter;

    /**
     * @var Collection<Character>
     */
    private $characters;

    /**
     * @var Collection<Item>
     */
    private $items;

    /**
     * @var Collection<Location>
     */
    private $locations;

    /**
     * @var Collection<Event>
     */
    private $events;

    /**
     * @var Collection<Publication>
     */
    private $publications;

    /**
     * @param UuidInterface $id
     * @param ShortText $title
     * @param Chapter|null $chapter
     * @param Author $author
     */
    public function __construct(UuidInterface $id, ShortText $title, ?Chapter $chapter, Author $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->chapter = $chapter;

        $this->characters = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->publications = new ArrayCollection();
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
     * @param LongText|null $text
     * @param Chapter|null $chapter
     * @return void
     */
    public function edit(ShortText $title, ?LongText $text, ?Chapter $chapter): void
    {
        $this->title = $title;
        $this->text = $text;
        $this->chapter = $chapter;

        $this->update();
    }

    public function publish(ShortText $title, LongText $parsedContent, bool $visible): void
    {
        Assertion::notNull($this->locale, "Cannot publish scene \"{$this->id->toString()}\" without a locale");
        $this->publications->add(
            new Publication(
                Uuid::uuid4(),
                $this->createdBy,
                $title,
                $parsedContent,
                $visible,
                $this->locale
            )
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }

    public function getText(): ?LongText
    {
        return $this->text;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getBook(): ?Book
    {
        return null !== $this->chapter && null !== $this->chapter->getBook()
            ? $this->chapter->getBook()
            : null
        ;
    }

    public function getCharacters(): array
    {
        return $this->characters->toArray();
    }

    public function addCharacter(Character $character): void
    {
        if (true === $this->characters->contains($character)) {
            return;
        }

        $this->characters->add($character);

        $this->update();
    }

    public function removeCharacter(Character $character): void
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    public function getLocations(): array
    {
        return $this->locations->toArray();
    }

    public function addLocation(Location $location): void
    {
        if (true === $this->locations->contains($location)) {
            return;
        }

        $this->locations->add($location);

        $this->update();
    }

    public function removeLocation(Location $location): void
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }

    public function addItem(Item $item): void
    {
        if (true === $this->items->contains($item)) {
            return;
        }

        $this->items->add($item);

        $this->update();
    }

    public function removeItem(Item $item): void
    {
        $this->items->removeElement($item);
        $this->update();
    }

    public function getEvents(): array
    {
        return $this->events->toArray();
    }

    public function getPublications(): array
    {
        return $this->publications->toArray();
    }

    public function getCurrentPublication(string $locale): ?Publication
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('locale', $locale))
            ->andWhere(Criteria::expr()->eq('visible', true))
            ->orderBy(['createdAt' => 'DESC'])
            ->setMaxResults(1)
        ;

        $result = $this->publications->matching($criteria)->first();
        return true === $result instanceof Publication ? $result : null;
    }
}
