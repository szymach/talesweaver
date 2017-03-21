<?php

namespace AppBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;

/**
 * @author Piotr Szymaszek
 */
class Character
{
    use Traits\AvatarTrait, Traits\TimestampableTrait, Traits\TranslatableTrait;

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
     * @Translatable\Translatable(mappedBy="translations")
     * @var string
     */
    private $description;

    /**
     * @var Collection
     */
    private $scenes;

    /**
     * @var Collection
     */
    private $chapters;

    /**
     * @var Book
     */
    private $book;

    /**
     * @var Collection
     */
    private $items;

    /**
     * @var Collection
     */
    private $locations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return (string) $this->name;
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
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->update();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param Scene $scene
     */
    public function addScene(Scene $scene)
    {
        if (!$this->scenes->contains($scene)) {
            $this->assertSceneForTheSameBook($scene);
            $this->scenes[] = $scene;
            if ($scene->getChapter()) {
                $this->addChapter($scene->getChapter());
            }
            $this->update();
        }
    }

    /**
     * @param Scene $scene
     */
    public function removeScene(Scene $scene)
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getScenes()
    {
        return $this->scenes;
    }

    public function addChapter(Chapter $chapter)
    {
        if (!$this->chapters->contains($chapter)) {
            $this->assertChapterFromTheSameBook($chapter);
            $chapter->addCharacter($this);
            $this->chapters[] = $chapter;
            if ($chapter->getBook()) {
                $this->setBook($chapter->getBook());
            }
            $this->update();
        }
    }

    public function removeChapter(Chapter $chapter)
    {
        $this->chapters->removeElement($chapter);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $this->update();
        }
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Location $location
     */
    public function addLocation(Location $location)
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $this->update();
        }
    }

    /**
     * @param Location $location
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    /**
     * @return Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    private function assertSceneForTheSameBook(Scene $scene)
    {
        if (empty($this->scenes)) {
            return;
        }

        $callback = function (Scene $currentScene) use ($scene) {
            if ($currentScene->getChapter() && !$scene->getChapter()) {
                throw new DomainException(sprintf(
                    'Tried to assign scene "%s" without a chapter to character "%s"'
                ));
            }

            if (!$currentScene->getChapter() && $scene->getChapter()) {
                throw new DomainException(sprintf(
                    'Tried to assign scene "%s" with a chapter to character "%s"'
                ));
            }
        };

        $this->scenes->map($callback);
    }

    private function assertChapterFromTheSameBook(Chapter $chapter)
    {
        if (!$this->book && !$chapter->getBook()) {
            // No book to check
            return;
        }

        if (!$this->book && $chapter->getBook()) {
            // New book
            return;
        }

        if ($this->book !== $chapter->getBook()) {
            throw new DomainException(sprintf(
                'Character "%s" is already assigned to book "%s", but tried to assign it to book "%s"',
                $this->id,
                $this->book->getId(),
                $chapter->getBook()->getId()
            ));
        }
    }
}
