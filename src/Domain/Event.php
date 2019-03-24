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

class Event
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var Location|null
     */
    private $location;

    /**
     * @var Character[]|Collection
     */
    private $characters;

    /**
     * @var Item[]|Collection
     */
    private $items;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @param UuidInterface $id
     * @param ShortText $name
     * @param LongText|null $description
     * @param Location|null $location
     * @param Scene $scene
     * @param Author $author
     * @param Character[] $characters
     */
    public function __construct(
        UuidInterface $id,
        ShortText $name,
        ?LongText $description,
        ?Location $location,
        Scene $scene,
        Author $author,
        array $characters = [],
        array $items = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
        $this->scene = $scene;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
        $this->characters = new ArrayCollection($characters);
        $this->items = new ArrayCollection($items);
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param ShortText $name
     * @param LongText|null $description
     * @param Location|null $location
     * @param Character[] $characters
     * @param Item[] $items
     * @return void
     */
    public function edit(
        ShortText $name,
        ?LongText $description,
        ?Location $location,
        array $characters,
        array $items
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
        $this->characters = new ArrayCollection($characters);
        $this->items = new ArrayCollection($items);
        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getCharacters(): array
    {
        return $this->characters->toArray();
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }
}
