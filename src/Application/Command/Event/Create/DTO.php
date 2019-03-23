<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Character[]
     */
    private $characters;

    /**
     * @var Item[]
     */
    private $items;

    public function __construct(Scene $scene)
    {
        $this->characters = [];
        $this->items = [];
        $this->scene = $scene;
    }

    public function toCommand(UuidInterface $id, Scene $scene): Command
    {
        Assertion::notNull($this->name);
        return new Command(
            $id,
            $scene,
            new ShortText($this->name),
            $this->characters,
            $this->items
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getScene(): Scene
    {
        return $this->scene;
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
