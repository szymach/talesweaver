<?php

namespace AppBundle\Book\Edit;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $introduction;

    /**
     * @var string
     */
    private $expansion;

    /**
     * @var string
     */
    private $ending;

    /**
     * @var Collection
     */
    private $chapters;

    public function __construct(Book $book)
    {
        $this->title = $book->getTitle();
        $this->description = $book->getDescription();
        $this->introduction = $book->getIntroduction();
        $this->expansion = $book->getExpansion();
        $this->ending = $book->getEnding();
        $this->chapters = new ArrayCollection();
        foreach ($book->getChapters() as $chapter) {
            $this->addChapter($chapter);
        }
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $introduction
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    }

    /**
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param string $expansion
     */
    public function setExpansion($expansion)
    {
        $this->expansion = $expansion;
    }

    /**
     * @return string
     */
    public function getExpansion()
    {
        return $this->expansion;
    }

    /**
     * @param string $ending
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;
    }

    /**
     * @return string
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * @return Chapter[]|Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * @param Chapter $chapter
     */
    public function addChapter(Chapter $chapter)
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
        }
    }

    /**
     * @param Chapter $chapter
     */
    public function removeChapter(Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
    }
}
