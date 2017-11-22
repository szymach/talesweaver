<?php

declare(strict_types=1);

namespace Domain\Scene\Edit;

use AppBundle\Entity\Scene;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Scene
     */
    private $scene;

    public function __construct(DTO $dto, Scene $scene)
    {
        $this->dto = $dto;
        $this->scene = $scene;
    }

    public function perform(): void
    {
        $this->scene->edit($this->dto);
    }
}
