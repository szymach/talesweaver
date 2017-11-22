<?php

declare(strict_types=1);

namespace Domain\Item\AddToScene;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use Domain\Security\UserAccessInterface;

class Command implements UserAccessInterface
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Item
     */
    private $item;

    public function __construct(Scene $scene, Item $item)
    {
        $this->scene = $scene;
        $this->item = $item;
    }

    public function perform(): void
    {
        $this->scene->addItem($this->item);
    }

    public function isAllowed(User $user): bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->item->getCreatedBy()->getId()
            && $user->getId() === $this->item->getCreatedBy()->getId()
        ;
    }
}
