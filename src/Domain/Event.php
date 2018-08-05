<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Talesweaver\Domain\ValueObject\ShortText;

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
     * @var ShortText
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @param UuidInterface $id
     * @param ShortText $name
     * @param JsonSerializable $model
     * @param Scene $scene
     * @param Author $author
     */
    public function __construct(
        UuidInterface $id,
        ShortText $name,
        JsonSerializable $model,
        Scene $scene,
        Author $author
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->model = $model;
        $this->scene = $scene;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param ShortText $name
     * @param JsonSerializable $model
     * @return void
     */
    public function edit(ShortText $name, JsonSerializable $model): void
    {
        $this->name = $name;
        $this->model = $model;
        $this->update();
    }

    public function setParsedModel(JsonSerializable $model): void
    {
        $this->model = $model;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ShortText
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
