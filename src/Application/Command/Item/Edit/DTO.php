<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Item\Edit;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Item;

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

    public function __construct(Item $item)
    {
        $this->id = $item->getId();
        $this->name = (string) $item->getName();
        $this->description = (string) $item->getDescription();
        $this->avatar = $item->getAvatar();
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
