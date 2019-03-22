<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Event\Create;

use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

final class DTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var Character[]
     */
    private $characters;

    public function __construct(Scene $scene)
    {
        $this->characters = [];
        $this->scene = $scene;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function setCharacters(array $characters): void
    {
        $this->characters = $characters;
    }
}
