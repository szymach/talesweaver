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
    private $name;

    /**
     * @var Collection
     */
    private $paragraphs;

    public function __construct()
    {
        $this->paragraphs = new ArrayCollection();
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
     * @param string $name
     *
     * @return Chapter
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
     * @param Paragraph $paragraph
     *
     * @return Chapter
     */
    public function addParagraph(Paragraph $paragraph)
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $paragraph->setChapter($this);
            $this->paragraphs = $paragraph;
        }

        return $this;
    }

    /**
     * @param Paragraph $paragraph
     */
    public function removeParagraph(Paragraph $paragraph)
    {
        $this->paragraphs->removeElement($paragraph);
        $paragraph->setChapter(null);
    }

    /**
     * @return Collection
     */
    public function getParagraphs()
    {
        return $this->paragraphs;
    }
}
