<?php

namespace AppBundle\Entity;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
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
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

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

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(?JsonSerializable $model)
    {
        $this->model = $model;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }
}
