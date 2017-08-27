<?php

namespace AppBundle\Entity;

use AppBundle\Location\Create\DTO as CreateDTO;
use AppBundle\Location\Edit\DTO as EditDTO;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Location
{
    use Traits\AvatarTrait, Traits\TimestampableTrait, Traits\TranslatableTrait;

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
     * @param \AppBundle\Location\Create\DTO $dto
     */
    public function __construct(UuidInterface $id, CreateDTO $dto)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();

        $dto->getScene()->addLocation($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param \AppBundle\Location\Edit\DTO $dto
     */
    public function edit(EditDTO $dto) : void
    {
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function setName(?string $name) : void
    {
        $this->name = $name;
        $this->update();
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
        $this->update();
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function addScene(Scene $scene) : void
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $this->update();
        }
    }

    public function removeScene(Scene $scene) : void
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    public function getScenes() : Collection
    {
        return $this->scenes;
    }

    public function getCharacters() : Colllection
    {
        return $this->characters;
    }

    public function getItems() : Colllection
    {
        return $this->items;
    }
}
