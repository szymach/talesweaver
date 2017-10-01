<?php

namespace Domain\Event\Edit;

use AppBundle\Entity\Event;
use JsonSerializable;

class DTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var JsonSerializable
     */
    private $model;

    public function __construct(Event $event)
    {
        $this->name = $event->getName();
        $this->model = $event->getModel();
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getModel() : ?JsonSerializable
    {
        return $this->model;
    }

    public function setModel(?JsonSerializable $model)
    {
        $this->model = $model;
    }
}
