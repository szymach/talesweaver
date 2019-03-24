<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var Character[]
     */
    private $characters;

    /**
     * @var Item[]
     */
    private $items;

    public function __construct(Event $event)
    {
        $this->id = $event->getId();
        $this->name = (string) $event->getName();
        $this->description = null !== $event->getDescription() ? (string) $event->getDescription() : null;
        $this->characters = $event->getCharacters();
        $this->items = $event->getItems();
    }

    public function toCommand(Event $event): Command
    {
        Assertion::notNull($this->name);
        return new Command(
            $event,
            new ShortText($this->name),
            LongText::fromNullableString($this->description),
            $this->characters,
            $this->items
        );
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function setCharacters(array $characters): void
    {
        $this->characters = $characters;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
