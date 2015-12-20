<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

class Chapter
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
    private $title;

    /**
     * @var Collection
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
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
     * @param string $title
     *
     * @return Chapter
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
     * @param Section $section
     *
     * @return Chapter
     */
    public function addSection(Section $section)
    {
        if (!$this->sections->contains($section)) {
            $section->setChapter($this);
            $this->sections = $section;
        }

        return $this;
    }

    /**
     * @param Section $section
     */
    public function removeSection(Section $section)
    {
        $this->sections->removeElement($section);
        $section->setChapter(null);
    }

    /**
     * @return Collection
     */
    public function getSections()
    {
        return $this->sections;
    }
}
