<?php

namespace AppBundle\Entity;

use AppBundle\Event\Create\DTO;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Event
{
    use Traits\TranslatableTrait;

    /**
     * @var UuidInterface
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

    public function __construct(UuidInterface $id, DTO $dto)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->model = $dto->getModel();
        $this->scene = $dto->getScene();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getScene() : Scene
    {
        return $this->scene;
    }
}
