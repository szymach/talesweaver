<?php

namespace Domain\Character\AddToScene;

use AppBundle\Entity\Character;
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
     * @var Character
     */
    private $character;

    public function __construct(Scene $scene, Character $character)
    {
        $this->scene = $scene;
        $this->character = $character;
    }

    public function perform() : void
    {
        $this->scene->addCharacter($this->character);
    }

    public function isAllowed(User $user) : bool
    {
        return $this->scene->getCreatedBy()->getId() === $this->character->getCreatedBy()->getId()
            && $user->getId() === $this->character->getCreatedBy()->getId()
        ;
    }
}
