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
     * @var Scene
     */
    private $scene;

    /**
     * @var Character[]|Collection
     */
    private $characters;

    /**
     * @var Item[]|Collection
     */
    private $items;

    /**
     * @param UuidInterface $id
     * @param ShortText $name
     * @param Scene $scene
     * @param Author $author
     * @param Character[] $characters
     */
    public function __construct(
        UuidInterface $id,
        ShortText $name,
        Scene $scene,
        Author $author,
        array $characters = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->scene = $scene;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
        $this->characters = new ArrayCollection($characters);
        $this->items = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param ShortText $name
     * @param Character[] $characters
     * @return void
     */
    public function edit(ShortText $name, array $characters): void
    {
        $this->name = $name;
        $this->characters = $characters;
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
