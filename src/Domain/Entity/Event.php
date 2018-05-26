<?php

declare(strict_types=1);

namespace Domain\Entity;

use App\JSON\EventParser;
use Assert\Assertion;
use DateTimeImmutable;
use Domain\Entity\Traits\CreatedByTrait;
use Domain\Entity\Traits\TimestampableTrait;
use Domain\Entity\Traits\TranslatableTrait;
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
        Assertion::notBlank($name, sprintf(
            'Cannot create an event without a name for author "%s" and scene "%s"!',
            $author->getId(),
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
