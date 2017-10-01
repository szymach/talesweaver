<?php

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

    public function perform()
    {
        $this->scene->edit($this->dto);
    }
}
