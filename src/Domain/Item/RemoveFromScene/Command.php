<?php

namespace Domain\Item\RemoveFromScene;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use AppBundle\Security\UserAccessInterface;

class Command implements UserAccessInterface
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene, Item $item)
    {
        $this->scene = $scene;
        $this->item = $item;
    }

    public function perform() : void
    {
        $this->scene->removeItem($this->item);
    }

    public function isAllowed(User $user) : bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->item->getCreatedBy()->getId()
            && $user->getId() === $this->item->getCreatedBy()->getId()
        ;
    }
}
