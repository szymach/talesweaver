<?php

namespace AppBundle\Entity;

use AppBundle\Book\Edit\DTO;
use Assert\Assert;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use Ramsey\Uuid\Uuid;

class Book
{
    use Traits\TimestampableTrait, Traits\TranslatableTrait;

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
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $description;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $introduction;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $expansion;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $ending;

    /**
     * @var Collection
     */
    private $chapters;

    public function __construct(Uuid $id, string $title)
    {
        Assert::that($title)->notBlank('Cannot create book without a title!');

        $this->id = $id;
        $this->title = $title;
        $this->chapters = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function edit(DTO $dto)
    {
        $this->title = $dto->getTitle();
        $this->description = $dto->getDescription();
        $this->introduction = $dto->getIntroduction();
        $this->expansion = $dto->getExpansion();
        $this->ending = $dto->getEnding();
        $currentChapters = $this->chapters;
        $this->chapters = new ArrayCollection();

        $newChapters = $dto->getChapters();
        foreach ($dto->getChapters() as $chapter) {
            $this->addChapter($chapter);
        }
        foreach ($currentChapters as $chapterToCheck) {
            if (!$newChapters->contains($chapterToCheck)) {
                $this->removeChapter($chapterToCheck);
            }
        }

        $this->update();
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
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->update();
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
        $this->update();
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
        $this->update();
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
        $this->update();
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
        $this->update();
    }

    /**
     * @return string
     */
    public function getEnding()
    {
        return $this->ending;
    }

    /**
     * @param Chapter $chapter
     */
    public function addChapter(Chapter $chapter)
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setBook($this);
            $this->update();
        }
    }

    /**
     * @param Chapter $chapter
     */
    public function removeChapter(Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
        $chapter->setBook(null);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }
}
