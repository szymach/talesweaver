<?php

namespace AppBundle\Event\Edit;

use AppBundle\Entity\Event;

class Command
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var DTO
     */
    private $dto;

    public function __construct(Event $event, DTO $dto)
    {
        $this->event = $event;
        $this->dto = $dto;
    }

    public function perform() : void
    {
        $this->event->edit($this->dto);
    }
}
