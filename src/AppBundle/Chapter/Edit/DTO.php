<?php

namespace AppBundle\Chapter\Edit;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Book
     */
    private $book;

    /**
     * @var Collection|Scene[]
     */
    private $scenes;

    public function __construct(Chapter $chapter)
    {
        $this->title = $chapter->getTitle();
        $this->scenes = new ArrayCollection();
        $this->book = $chapter->getBook();
        foreach ($chapter->getScenes() as $scene) {
            $this->addScene($scene);
        }
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book)
    {
        $this->book = $book;
    }

    /**
     * @return Scene[]
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
        }
    }

    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
    }
}
