<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Paragraph
{
    use Traits\TranslatableTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $text;

    /**
     * @var Chapter
     */
    private $chapter;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $text
     *
     * @return Paragraph
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Chapter $chapter
     *
     * @return Paragraph
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
