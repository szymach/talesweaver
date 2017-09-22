<?php

namespace AppBundle\Entity;

use AppBundle\Character\Create;
use AppBundle\Entity\Traits\AvatarTrait;
use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\TimestampableTrait;
use AppBundle\Entity\Traits\TranslatableTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\UuidInterface;

class Character
{
    use AvatarTrait, CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
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
     * @param User $author
     */
    public function __construct(UuidInterface $id, Create\DTO $dto, User $author)
    {
        $this->id = $id;
        $this->name = $dto->getName();
        $this->description = $dto->getDescription();

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdBy = $author;
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
        $this->update();
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getBook() : ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book) : void
    {
        $this->book = $book;
    }

    public function addScene(Scene $scene) : void
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

    public function removeScene(Scene $scene) : void
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    public function getScenes() : Collection
    {
        return $this->scenes;
    }

    public function addChapter(Chapter $chapter) : void
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

    public function removeChapter(Chapter $chapter) : void
    {
        $this->chapters->removeElement($chapter);
        $this->update();
    }

    public function getChapters() : Collection
    {
        return $this->chapters;
    }

    public function addItem(Item $item) : void
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $this->update();
        }
    }

    public function removeItem(Item $item) : void
    {
        $this->items->removeElement($item);
        $this->update();
    }

    public function getItems() : Collection
    {
        return $this->items;
    }

    public function addLocation(Location $location) : void
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $this->update();
        }
    }

    public function removeLocation(Location $location) : void
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    public function getLocations() : Collection
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
