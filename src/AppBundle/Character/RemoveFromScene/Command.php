<?php

namespace AppBundle\Character\RemoveFromScene;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;

class Command
{
    /**
     * @var Character
     */
    private $character;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Character $character, Scene $scene)
    {
        $this->character = $character;
        $this->scene = $scene;
    }

    public function perform()
    {
        $this->scene->removeCharacter($this->character);
    }
}
