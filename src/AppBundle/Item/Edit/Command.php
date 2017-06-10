<?php

namespace AppBundle\Item\Edit;

use AppBundle\Entity\Item;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Item
     */
    private $item;

    public function __construct(DTO $dto, Item $item)
    {
        $this->dto = $dto;
        $this->item = $item;
    }

    public function perform()
    {
        $this->item->edit($this->dto);
    }
}
