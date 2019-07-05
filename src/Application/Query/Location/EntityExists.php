<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Scene;

final class EntityExists
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var UuidInterface|null
     */
    private $id;

    /**
     * @var Scene|null
     */
    private $scene;

    public function __construct(string $name, ?UuidInterface $id, ?Scene $scene)
    {
        $this->name = $name;
        $this->id = $id;
        $this->scene = $scene;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }
}
