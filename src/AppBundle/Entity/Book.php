<?php

namespace AppBundle\Entity;

use Domain\Book\Edit\DTO;
use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\TimestampableTrait;
use AppBundle\Entity\Traits\TranslatableTrait;
use Assert\Assert;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Book
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

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

    /**
     * @param UuidInterface $id
     * @param string $title
     * @param User $author
     */
    public function __construct(UuidInterface $id, string $title, User $author)
    {
        Assert::that($title)->notBlank('Cannot create a book without a title!');

        $this->id = $id;
        $this->title = $title;
        $this->chapters = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
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

    public function getTitle() : ?string
    {
        return $this->title;
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
