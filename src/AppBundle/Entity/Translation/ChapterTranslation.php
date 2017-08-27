<?php

namespace AppBundle\Entity\Translation;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Traits\LocaleTrait;

class ChapterTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Chapter
     */
    private $chapter;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title) : void
    {
        $this->title = $title;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setChapter(?Chapter $chapter) : void
    {
        $this->chapter = $chapter;
    }

    public function getChapter() : ?Chapter
    {
        return $this->chapter;
    }
}
