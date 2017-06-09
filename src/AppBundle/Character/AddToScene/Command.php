<?php

namespace AppBundle\Character\AddToScene;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;

class Command
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

    public function perform()
    {
        $this->scene->addCharacter($this->character);
    }
}
