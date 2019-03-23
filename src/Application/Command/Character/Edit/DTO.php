<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\File;
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
     * @var object|null
     */
    private $avatar;

    /**
     * @var Scene[]
     */
    private $scenes;

    public function __construct(Character $character)
    {
        $this->id = $character->getId();
        $this->name = (string) $character->getName();
        $this->avatar = null !== $character->getAvatar() ? $character->getAvatar()->getValue() : null;
        $this->description = null !== $character->getDescription() ? (string) $character->getDescription() : null;
        $this->scenes = $character->getScenes();
    }

    public function toCommand(Character $character): Command
    {
        Assertion::notNull($this->name);
        return new Command(
            $character,
            new ShortText($this->name),
            LongText::fromNullableString($this->description),
            File::fromNullableValue($this->avatar)
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

    public function getAvatar(): ?object
    {
        return $this->avatar;
    }

    public function setAvatar(?object $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getScenes(): array
    {
        return $this->scenes;
    }
}
