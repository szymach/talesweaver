<?php

declare(strict_types=1);

namespace Application\Event\Edit;

use Domain\Event;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class DTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var JsonSerializable
     */
    private $model;

    /**
     * @var UuidInterface
     */
    private $scene;

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->model = $event->getModel();
        $this->scene = $event->getScene()->getId();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getModel(): ?JsonSerializable
    {
        return $this->model;
    }

    public function setModel(?JsonSerializable $model): void
    {
        $this->model = $model;
    }

    public function getScene(): UuidInterface
    {
        return $this->scene;
    }
}
