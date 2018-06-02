<?php

declare(strict_types=1);

namespace Domain\Entity;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Entity\Traits\AvatarTrait;
use Domain\Entity\Traits\CreatedByTrait;
use Domain\Entity\Traits\TimestampableTrait;
use Domain\Entity\Traits\TranslatableTrait;
use DomainException;
use FSi\DoctrineExtensions\Uploadable\File;
use InvalidArgumentException;
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
        Assertion::notBlank($name, sprintf(
            'Cannot create a character without a name for user "%s"!',
            $author->getId()
        ));
        $this->validateAvatar($id, $avatar);

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
        return $this->name;
    }

    /**
     * @param string $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @return void
     */
    public function edit(string $name, ?string $description, $avatar): void
    {
        Assertion::notBlank($name, sprintf(
            'Tried to set an empty name on character with id "%s"!',
            $this->id->toString()
        ));
        $this->validateAvatar($this->id, $avatar);

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
        if (true === $this->scenes->contains($scene)) {
            return;
        }

        $this->assertSceneFromTheSameChapter($scene);
        $this->scenes[] = $scene;
        if (null !== $scene->getChapter()) {
            $this->addChapter($scene->getChapter());
        }

        $this->update();
    }

    public function removeScene(Scene $scene): void
    {
        $this->scenes->removeElement($scene);
        if (null !== $scene->getChapter()) {
            $this->removeChapterIfNoRelatedScenesLeft($scene->getChapter());
        }

        $this->update();
    }

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addChapter(Chapter $chapter): void
    {
        if (true === $this->chapters->contains($chapter)) {
            return;
        }

        $this->assertChapterFromTheSameBook($chapter);
        $chapter->addCharacter($this);
        $this->chapters[] = $chapter;
        if (null !== $chapter->getBook()) {
            $this->setBook($chapter->getBook());
        }

        $this->update();
    }

    public function removeChapter(Chapter $chapter): void
    {
        $this->chapters->removeElement($chapter);
        if (null !== $chapter->getBook()) {
            $this->removeBookIfNoRelatedChaptersLeft($chapter->getBook());
        }

        $this->update();
    }

    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addItem(Item $item): void
    {
        if (true === $this->items->contains($item)) {
            return;
        }

        $this->items[] = $item;
        $this->update();
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
        if (true === $this->locations->contains($location)) {
            return;
        }

        $this->locations[] = $location;
        $this->update();
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

    private function assertSceneFromTheSameChapter(Scene $scene): void
    {
        if (true === $this->scenes->isEmpty()) {
            return;
        }

        $chapters = $this->chapters->toArray();
        if (null === $scene->getChapter() && 0 !== count($chapters)) {
            throw new DomainException(sprintf(
                'Scene "%s" is inconsistent with other scenes from character "%s"',
                $scene->getId()->toString(),
                $this->id->toString()
            ));
        }
    }

    private function assertChapterFromTheSameBook(Chapter $chapter): void
    {
        if (0 === $this->chapters->count()) {
            return;
        }

        if ($this->book !== $chapter->getBook()) {
            throw new DomainException(sprintf(
                'Chapter "%s" is inconsistent with character\'s "%s" chapters',
                $chapter->getId()->toString(),
                $this->id->toString()
            ));
        }
    }

    private function removeChapterIfNoRelatedScenesLeft(Chapter $chapter): void
    {
        $relatedScenesLeft = $this->scenes->filter(function (Scene $scene) use ($chapter): bool {
            return $scene->getChapter() === $chapter;
        })->count();

        if (0 !== $relatedScenesLeft) {
            return;
        }

        $this->removeChapter($chapter);
    }

    private function removeBookIfNoRelatedChaptersLeft(Book $book): void
    {
        $relatedChaptersLeft = $this->chapters->filter(function (Chapter $chapter) use ($book): bool {
            return $chapter->getBook() === $book;
        })->count();

        if (0 !== $relatedChaptersLeft) {
            return;
        }

        $this->setBook(null);
    }

    private function validateAvatar(UuidInterface $id, $avatar): void
    {
        if (null !== $avatar
            && false === $avatar instanceof File
            && false === $avatar instanceof SplFileInfo
        ) {
            throw new InvalidArgumentException(sprintf(
                'Character\'s "%s" avatar must be either of instance "%s" or "%s", got "%s"',
                $id->toString(),
                File::class,
                SplFileInfo::class,
                is_object($avatar) ? get_class($avatar) : gettype($avatar)
            ));
        }
    }
}
