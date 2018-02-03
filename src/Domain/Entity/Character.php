<?php

declare(strict_types=1);

namespace Domain\Entity;

use Domain\Entity\Traits\AvatarTrait;
use Domain\Entity\Traits\CreatedByTrait;
use Domain\Entity\Traits\TimestampableTrait;
use Domain\Entity\Traits\TranslatableTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use FSi\DoctrineExtensions\Uploadable\File;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;

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
     * @param Scene $scene
     * @param string $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @param User $author
     */
    public function __construct(
        UuidInterface $id,
        Scene $scene,
        string $name,
        ?string $description,
        $avatar,
        User $author
    ) {
        $this->validateAvatar($avatar);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();

        $scene->addCharacter($this);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @return void
     */
    public function edit(
        string $name,
        ?string $description,
        $avatar
    ): void {
        $this->validateAvatar($avatar);

        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;

        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function addScene(Scene $scene): void
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

    public function removeScene(Scene $scene): void
    {
        $this->scenes->removeElement($scene);
        $this->update();
    }

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addChapter(Chapter $chapter): void
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

    public function removeChapter(Chapter $chapter): void
    {
        $this->chapters->removeElement($chapter);
        $this->update();
    }

    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addItem(Item $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $this->update();
        }
    }

    public function removeItem(Item $item): void
    {
        $this->items->removeElement($item);
        $this->update();
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addLocation(Location $location): void
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $this->update();
        }
    }

    public function removeLocation(Location $location): void
    {
        $this->locations->removeElement($location);
        $this->update();
    }

    public function getLocations(): Collection
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
