<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Assert\Assertion;
use DateTimeImmutable;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;

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
     * @param Author $author
     */
    public function __construct(
        UuidInterface $id,
        string $name,
        JsonSerializable $model,
        Scene $scene,
        Author $author
    ) {
        Assertion::notBlank($name, sprintf(
            'Cannot create an event without a name for author "%s" and scene "%s"!',
            $author->getId()->toString(),
            $scene->getId()->toString()
        ));

        $this->id = $id;
        $this->name = $name;
        $this->model = $model;
        $this->scene = $scene;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
    }

    /**
     * @param string $name
     * @param JsonSerializable $model
     * @return void
     */
    public function edit(string $name, JsonSerializable $model): void
    {
        Assertion::notBlank(
            $name,
            sprintf('Tried to set an empty name on event with id "%s"!', $this->id->toString())
        );

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
