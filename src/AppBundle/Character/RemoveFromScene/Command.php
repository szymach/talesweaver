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

    public function __construct(Scene $scene, Character $character)
    {
        $this->scene = $scene;
        $this->character = $character;
    }

    public function perform()
    {
        $this->scene->removeCharacter($this->character);
    }
}
