<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TranslatableTrait;
use AppBundle\Event\Create;
use AppBundle\Event\Edit;
use AppBundle\JSON\EventParser;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Event
{
    use TranslatableTrait;

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

    public function __construct(UuidInterface $id, Create\DTO $dto)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->model = $dto->getModel();
        $this->scene = $dto->getScene();
    }

    public function edit(Edit\DTO $dto) : void
    {
        $this->name = $dto->getName();
        $this->model = $dto->getModel();
    }

    public function parseModel(EventParser $parser) : void
    {
        $this->model = $parser->parse($this);
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(?string $name) : void
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
