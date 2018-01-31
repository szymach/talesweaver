<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\AvatarTrait;
use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\TranslatableTrait;
use Domain\Location\Create\DTO as CreateDTO;
use Domain\Location\Edit\DTO as EditDTO;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Location
{
    use AvatarTrait, CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Collection
     */
    private $translations;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

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
     * @param \App\Location\Create\DTO $dto
     * @param User $author
     */
    public function __construct(UuidInterface $id, CreateDTO $dto, User $author)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
        $this->avatar = $dto->getAvatar();

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();

        $dto->getScene()->addLocation($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param \App\Location\Edit\DTO $dto
     */
    public function edit(EditDTO $dto): void
    {
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addScene(Scene $scene): void
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $this->update();
        }
    }

    public function removeScene(Scene $scene): void
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function getCharacters(): Colllection
    {
        return $this->characters;
    }

    public function getItems(): Colllection
    {
        return $this->items;
    }
}
