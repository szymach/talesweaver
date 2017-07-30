<?php

namespace AppBundle\Event\Edit;

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

    public function __construct(string $name, JsonSerializable $model)
    {
        $this->name = $name;
        $this->model = $model;
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
