<?php

namespace AppBundle\Entity;

use JsonSerializable;

class Event
{
    use Traits\TranslatableTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var JsonSerializable
     */
    private $model;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getModel(): ?JsonSerializable
    {
        return $this->model;
    }

    public function setModel(?JsonSerializable $model)
    {
        $this->model = $model;
    }

    public function getScene()
    {
        return $this->scene;
    }

    public function setScene(Scene $scene = null)
    {
        $this->scene = $scene;
    }
}
