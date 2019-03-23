<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Location;
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

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->name = (string) $location->getName();
        $this->description = null !== $location->getDescription() ? (string) $location->getDescription() : null;
        $this->avatar = $location->getAvatar();
    }

    public function toCommand(Location $location): Command
    {
        Assertion::notNull($this->name);
        return new Command(
            $location,
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
}
