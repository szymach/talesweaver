<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Item;
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

    public function __construct(Item $item)
    {
        $this->id = $item->getId();
        $this->name = (string) $item->getName();
        $this->description = null !== $item->getDescription() ? (string) $item->getDescription() : null;
        $this->avatar = $item->getAvatar();
    }

    public function toCommand(Item $item): Command
    {
        Assertion::notNull($this->name);
        return new Command(
            $item,
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

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }
}
