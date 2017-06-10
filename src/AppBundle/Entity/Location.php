<?php

namespace AppBundle\Entity;

use AppBundle\Location\Create\DTO as CreateDTO;
use AppBundle\Location\Edit\DTO as EditDTO;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use Ramsey\Uuid\UuidInterface;

class Location
{
    use Traits\AvatarTrait, Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $name;

    /**
     * @Translatable\Translatable(mappedBy="translations")
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
    private $items;

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

    public function edit(EditDTO $dto)
    {
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->update();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->update();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Scene $scene
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $this->update();
        }
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getScenes()
    {
        return $this->scenes;
    }

    /**
     * @return Collection
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $this->update();
        }
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
