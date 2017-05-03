<?php

namespace AppBundle\Model;

use AppBundle\Entity\Character;
use AppBundle\Entity\Item;
use AppBundle\Entity\Location;
use InvalidArgumentException;
use JsonSerializable;

class Meeting implements JsonSerializable
{
    /**
     * @var Character
     */
    private $root;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var Item
     */
    private $item;

    /**
     * @var Character
     */
    private $relation;

    public function jsonSerialize()
    {
        return [
            self::class => [
                'root' => $this->root ? [Character::class =>  $this->root->getId()] : null,
                'location' => $this->location ? [Location::class => $this->location->getId()] : null,
                'item' => $this->item ? [Item::class => $this->item->getId()] : null,
                'relation' => $this->relation ? [Character::class => $this->relation->getId()] : null,
            ]
        ];
    }

    public function getRoot(): ?Character
    {
        return $this->root;
    }

    public function setRoot(?Character $root)
    {
        $this->root = $root;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item)
    {
        $this->item = $item;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location)
    {
        $this->location = $location;
    }

    public function getRelation(): ?Character
    {
        return $this->relation;
    }

    public function setRelation(?Character $relation)
    {
        if ($this->root && $relation && ($this->root === $relation)) {
            throw new InvalidArgumentException(
                sprintf('Character %s cannot meet itself', $this->root->getId())
            );
        }

        $this->relation = $relation;
    }
}
