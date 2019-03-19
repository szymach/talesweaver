<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

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

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->name = (string) $event->getName();
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
}
