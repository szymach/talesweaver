<?php

declare(strict_types=1);

namespace Talesweaver\Application\Location\Edit;

use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Uploadable\File;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;
use Talesweaver\Domain\Location;
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
     * @var File|SplFileInfo
     */
    private $avatar;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    public function __construct(Location $location)
    {
        $this->id = $location->getId();
        $this->name = (string) $location->getName();
        $this->description = (string) $location->getDescription();
        $this->avatar = $location->getAvatar();
        $this->scenes = $location->getScenes();
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

    public function getScenes(): Collection
    {
        return $this->scenes;
    }
}
