<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Character\Edit;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

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

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    public function __construct(Character $character)
    {
        $this->id = $character->getId();
        $this->name = (string) $character->getName();
        $this->avatar = null !== $character->getAvatar() ? $character->getAvatar()->getValue() : null;
        $this->description = (string) $character->getDescription();
        $this->scenes = $character->getScenes();
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

    public function getScenes(): Collection
    {
        return $this->scenes;
    }
}
