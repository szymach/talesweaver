<?php

namespace AppBundle\Chapter\Edit;

use AppBundle\Entity\Chapter;

class Command
{
    /**
     * @var DTO
     */
    private $dto;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(DTO $dto, Chapter $chapter)
    {
        $this->dto = $dto;
        $this->chapter = $chapter;
    }

    public function perform()
    {
        $this->chapter->edit($this->dto);
    }
}
