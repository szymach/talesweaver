<?php

namespace AppBundle\Event\Create;

use AppBundle\Entity\Scene;
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

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(?string $name) : void
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

    public function getScene() : Scene
    {
        return $this->scene;
    }
}
