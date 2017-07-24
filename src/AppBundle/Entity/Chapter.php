<?php

namespace AppBundle\Entity;

use AppBundle\Chapter\Create;
use AppBundle\Chapter\Edit;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Chapter
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
     * @var Book
     */
    private $book;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $characters;

    /**
     * @param UuidInterface $id
     * @param \AppBundle\Chapter\Create\DTO $dto
     */
    public function __construct(UuidInterface $id, Create\DTO $dto)
    {
        $this->id = $id;
        $this->title = $dto->getTitle();
        if ($dto->getBook()) {
            $this->book = $dto->getBook();
        }
        $this->characters = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param \AppBundle\Chapter\Edit\DTO $dto
     */
    public function edit(Edit\DTO $dto)
    {
        $this->title = $dto->getTitle();
        $this->book = $dto->getBook();

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
     * @return Scene[]|Collection
     */
    public function getScenes() : Collection
    {
        return $this->scenes;
    }

    /**
     * @param Character $character
     */
    public function addCharacter(Character $character)
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $this->update();
        }
    }

    /**
     * @param Character $character
     */
    public function removeCharacter(Character $character)
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    /**
     * @return Character[]|Collection
     */
    public function getCharacters() : Collection
    {
        return $this->characters;
    }

    /**
     * @param Book $book
     */
    public function setBook(?Book $book)
    {
        $this->book = $book;
    }

    /**
     * @return Book
     */
    public function getBook() : ?Book
    {
        return $this->book;
    }
}
