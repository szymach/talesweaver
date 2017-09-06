<?php

namespace AppBundle\Entity;

use AppBundle\Item\Create\DTO as CreateDTO;
use AppBundle\Item\Edit\DTO as EditDTO;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Item
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
     * @var Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $characters;

    /**
     * @var Collection
     */
    private $locations;

    public function __construct(UuidInterface $id, CreateDTO $dto)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();

        $dto->getScene()->addItem($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function edit(EditDTO $dto)
    {
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
        $this->update();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function addScene(Scene $scene) : void
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
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

    public function getCharacters() : Collection
    {
        return $this->characters;
    }

    public function getLocations() : Collection
    {
        return $this->locations;
    }
}
