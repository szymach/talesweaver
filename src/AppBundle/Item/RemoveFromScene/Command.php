<?php

namespace AppBundle\Item\RemoveFromScene;

use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;

class Command
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

    public function perform()
    {
        $this->scene->removeItem($this->item);
    }
}
