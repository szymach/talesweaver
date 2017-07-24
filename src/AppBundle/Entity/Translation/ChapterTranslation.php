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

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return ChapterTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Chapter $chapter
     *
     * @return ChapterTranslation
     */
    public function setChapter(Chapter $chapter = null)
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }
}
