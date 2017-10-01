<?php

namespace Domain\Event;

use AppBundle\Entity\Character;
use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use AppBundle\Security\UserAccessInterface;
use JsonSerializable;

class Meeting implements JsonSerializable, UserAccessInterface
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
     * @var Character
     */
    private $relation;

    public function jsonSerialize() : array
    {
        return [
            self::class => [
                'root' => $this->root ? [Character::class =>  $this->root->getId()] : null,
                'location' => $this->location ? [Location::class => $this->location->getId()] : null,
                'relation' => $this->relation ? [Character::class => $this->relation->getId()] : null,
            ]
        ];
    }

    public function isAllowed(User $user) : bool
    {
        if (!$this->root || !$this->location || !$this->relation) {
            return false;
        }

        $userId = $user->getId();
        return $this->root->getCreatedBy()->getId() === $userId
            && $this->location->getCreatedBy()->getId() === $userId
            && $this->relation->getCreatedBy()->getId() === $userId
        ;
    }

    public function getRoot() : ?Character
    {
        return $this->root;
    }

    public function setRoot(?Character $root)
    {
        $this->root = $root;
    }

    public function getLocation() : ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location)
    {
        $this->location = $location;
    }

    public function getRelation() : ?Character
    {
        return $this->relation;
    }

    public function setRelation(?Character $relation)
    {
        $this->relation = $relation;
    }
}
