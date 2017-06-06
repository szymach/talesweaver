<?php

namespace AppBundle\Scene\Edit;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct(Scene $scene)
    {
        $this->title = $scene->getTitle();
        $this->text = $scene->getText();
        $this->chapter = $scene->getChapter();
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getText() : ?string
    {
        return $this->text;
    }

    public function setText(?string $text)
    {
        $this->text = $text;
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
