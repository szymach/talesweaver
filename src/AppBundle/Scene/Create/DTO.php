<?php

namespace AppBundle\Scene\Create;

use AppBundle\Entity\Chapter;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(Chapter $chapter = null)
    {
        $this->chapter = $chapter;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getChapter() : ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter)
    {
        $this->chapter = $chapter;
    }
}
