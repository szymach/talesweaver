<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\TranslatableTrait;
use App\JSON\EventParser;
use DateTimeImmutable;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class Event
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var JsonSerializable
     */
    private $model;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @param UuidInterface $id
     * @param string $name
     * @param JsonSerializable $model
     * @param Scene $scene
     * @param User $author
     */
    public function __construct(
        UuidInterface $id,
        string $name,
        JsonSerializable $model,
        Scene $scene,
        User $author
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->model = $model;
        $this->scene = $scene;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
    }

    public function edit(string $name, JsonSerializable $model): void
    {
        $this->name = $name;
        $this->model = $model;
        $this->update();
    }

    public function parseModel(EventParser $parser): void
    {
        $this->model = $parser->parse($this);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }
}
