<?php

namespace AppBundle\Entity;

use AppBundle\Character\Create;
use AppBundle\Character\Edit;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use FSi\DoctrineExtensions\Translatable\Mapping\Annotation as Translatable;
use Ramsey\Uuid\UuidInterface;

class Character
{
    use Traits\AvatarTrait, Traits\TimestampableTrait, Traits\TranslatableTrait;

    /**
     * @var UuidInterface
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
     * @var Book
     */
    private $book;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    /**
     * @var Chapter[]|Collection
     */
    private $chapters;

    /**
     * @var Item[]|Collection
     */
    private $items;

    /**
     * @var Location[]|Collection
     */
    private $locations;

    /**
     * @param UuidInterface $id
     * @param \AppBundle\Character\Create\DTO $dto
     */
    public function __construct(UuidInterface $id, Create\DTO $dto)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();

        $dto->getScene()->addCharacter($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param \AppBundle\Character\Edit\DTO $dto
     */
    public function edit(Edit\DTO $dto)
    {
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();
        $this->avatar = $dto->getAvatar();
    }

    /**
     * @return UuidInterface
     */
    public function getId() : UuidInterface
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
        $this->update();
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
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
     * @return Book
     */
    public function getBook() : ?Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(?Book $book)
    {
        $this->book = $book;
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
     * @return Scene[]Collection
     */
    public function getScenes() : Collection
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
     * @return Chapter[]|Collection
     */
    public function getChapters() : Collection
    {
        return $this->chapters;
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
     * @return Item[]|Collection
     */
    public function getItems() : Collection
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
     * @return Location[]|Collection
     */
    public function getLocations() : Collection
    {
        return $this->locations;
    }

    /**
     * @param Scene $scene
     */
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

    /**
     * @param Chapter $chapter
     * @throws DomainException
     */
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
