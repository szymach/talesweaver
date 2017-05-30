<?php

namespace AppBundle\Entity;

use AppBundle\Chapter\Edit\DTO;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use Ramsey\Uuid\Uuid;

class Chapter
{
    use Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var Uuid
     */
    private $id;

    /**
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $title;

    /**
     * @var Book
     */
    private $book;

    /**
     * @var Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $characters;

    public function __construct(Uuid $id, string $title, ?Book $book)
    {
        $this->id = $id;
        $this->title = $title;
        if ($book) {
            $this->book = $book;
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

    public function edit(DTO $dto)
    {
        $this->title = $dto->getTitle();
        $currentScenes = $this->scenes;
        $this->scenes = new ArrayCollection();

        $newScenes = $dto->getScenes();
        foreach ($dto->getScenes() as $scene) {
            $this->addScene($scene);
        }
        foreach ($currentScenes as $sceneToCheck) {
            if (!$newScenes->contains($sceneToCheck)) {
                $this->removeScene($sceneToCheck);
            }
        }

        $this->update();
    }

    /**
     * @return Uuid
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
     * @param Scene $scene
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $scene->setChapter($this);
            $this->scenes->add($scene);
            $this->update();
        }
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
        $scene->setChapter(null);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getScenes()
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
     * @return Collection
     */
    public function getCharacters()
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
    public function getBook()
    {
        return $this->book;
    }
}
