<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Location\Edit;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Location;

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
     * @var string
     */
    private $description;

    /**
     * @var object
     */
    private $avatar;

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->name = (string) $location->getName();
        $this->description = (string) $location->getDescription();
        $this->avatar = $location->getAvatar();
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
