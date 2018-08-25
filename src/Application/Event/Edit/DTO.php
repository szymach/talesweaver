<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event\Edit;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Event;

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

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->name = (string) $event->getName();
        $this->model = $event->getModel();
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
}
