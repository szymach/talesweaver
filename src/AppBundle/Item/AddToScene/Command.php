<?php

namespace AppBundle\Item\AddToScene;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;

class Command
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

    public function perform()
    {
        $this->scene->addItem($this->item);
    }
}
