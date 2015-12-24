<?php

namespace AppBundle\Entity\Translation;

use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

use AppBundle\Entity\Section;
use AppBundle\Entity\Traits\LocaleTrait;

class SectionTranslation
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
     * @var string
     */
    private $text;

    /**
     * @var Section
     */
    private $section;

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
     * @return SectionTranslation
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
     * @param string $text
     *
     * @return SectionTranslation
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
     * @param Section $section
     *
     * @return SectionTranslation
     */
    public function setSection(Section $section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }
}
