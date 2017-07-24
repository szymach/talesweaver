<?php

namespace AppBundle\Entity;

use AppBundle\Book\Edit\DTO;
use Assert\Assert;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Book
{
    use Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Chapter[]|Collection
     */
    private $chapters;

    public function __construct(UuidInterface $id, string $title)
    {
        Assert::that($title)->notBlank('Cannot create a book without a title!');

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

        $this->update();
    }

    /**
     * @return UuidInterface
     */
    public function getId() : UuidInterface
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->update();
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
        $this->update();
    }

    /**
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
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
     * @return Chapter[]|Collection
     */
    public function getChapters() : Collection
    {
        return $this->chapters;
    }
}
