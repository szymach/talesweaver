<?php

declare(strict_types=1);

namespace Domain\Event\Edit;

use Domain\Entity\Event;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

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
     * @var UuidInterface
     */
    private $scene;

    public function __construct(Event $event)
    {
        $this->name = $event->getName();
        $this->model = $event->getModel();
        $this->scene = $event->getScene()->getId();
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
