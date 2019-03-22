<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;

final class DTO
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
     * @var Character[]
     */
    private $characters;

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->characters = $event->getCharacters();
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

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function setCharacters(array $characters): void
    {
        $this->characters = $characters;
    }
}
