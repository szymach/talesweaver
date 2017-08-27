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
        return (string) $this->title;
    }

    /**
     * @param \AppBundle\Chapter\Edit\DTO $dto
     */
    public function edit(Edit\DTO $dto) : void
    {
        $this->title = $dto->getTitle();
        $this->book = $dto->getBook();

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

    public function getScenes() : Collection
    {
        return $this->scenes;
    }

    public function addCharacter(Character $character) : void
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $this->update();
        }
    }

    public function removeCharacter(Character $character) : void
    {
        $this->characters->removeElement($character);
        $this->update();
    }

    public function getCharacters() : Collection
    {
        return $this->characters;
    }

    public function setBook(?Book $book) : void
    {
        $this->book = $book;
    }

    public function getBook() : ?Book
    {
        return $this->book;
    }
}
