<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Item;

use Ramsey\Uuid\UuidInterface;

class EntityExists
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var UuidInterface
     */
    private $sceneId;

    public function __construct(?string $name, ?UuidInterface $id, ?UuidInterface $sceneId)
    {
        $this->name = $name;
        $this->id = $id;
        $this->sceneId = $sceneId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getSceneId(): ?UuidInterface
    {
        return $this->sceneId;
    }
}
