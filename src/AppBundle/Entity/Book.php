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

    public function edit(DTO $dto) : void
    {
        $this->title = $dto->getTitle();
        $this->description = $dto->getDescription();

        $this->update();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function setTitle(?string $title) : void
    {
        $this->title = $title;
        $this->update();
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
        $this->update();
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function addChapter(Chapter $chapter) : void
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setBook($this);
            $this->update();
        }
    }

    public function removeChapter(Chapter $chapter) : void
    {
        $this->chapters->removeElement($chapter);
        $chapter->setBook(null);
        $this->update();
    }

    public function getChapters() : Collection
    {
        return $this->chapters;
    }
}
