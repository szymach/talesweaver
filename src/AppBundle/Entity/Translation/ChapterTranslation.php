<?php

namespace AppBundle\Entity\Translation;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

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
    private $name;

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
     * @param string $name
     *
     * @return ChapterTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
