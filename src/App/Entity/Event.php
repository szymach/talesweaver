<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\TranslatableTrait;
use App\JSON\EventParser;
use DateTimeImmutable;
use Domain\Event\Create;
use Domain\Event\Edit;
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
     * @param \App\Event\Create\DTO $dto
     * @param User $author
     */
    public function __construct(UuidInterface $id, Create\DTO $dto, User $author)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->model = $dto->getModel();
        $this->scene = $dto->getScene();
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $author;
    }

    public function edit(Edit\DTO $dto): void
    {
        $this->name = $dto->getName();
        $this->model = $dto->getModel();
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
