<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use FSi\DoctrineExtensions\Uploadable\File;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use SplFileInfo;
use Talesweaver\Domain\Traits\AvatarTrait;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class Character
{
    use AvatarTrait, CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $name;

    /**
     * @var LongText|null
     */
    private $description;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    /**
     * @param UuidInterface $id
     * @param Scene $scene
     * @param ShortText $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @param Author $author
     */
    public function __construct(
        UuidInterface $id,
        Scene $scene,
        ShortText $name,
        ?LongText $description,
        $avatar,
        Author $author
    ) {
        $this->validateAvatar($id, $avatar);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;

        $this->translations = new ArrayCollection();
        $this->scenes = new ArrayCollection([$scene]);
        $scene->addCharacter($this);
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param ShortText $name
     * @param string|null $description
     * @param File|SplFileInfo|null $avatar
     * @return void
     */
    public function edit(ShortText $name, ?LongText $description, $avatar): void
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

    public function getName(): ShortText
    {
        return $this->name;
    }

    public function getDescription(): ?LongText
    {
        return $this->description;
    }

    public function addScene(Scene $scene): void
    {
        if (true === $this->scenes->contains($scene)) {
            return;
        }

        $this->assertSceneConsistency($scene);
        $this->scenes[] = $scene;

        $this->update();
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

    private function assertSceneConsistency(Scene $scene): void
    {
        if (true === $this->scenes->isEmpty()) {
            return;
        }

        $sceneChapter = $scene->getChapter();
        $chapters = $this->getChapters();
        if (null === $sceneChapter && 0 !== count($chapters)) {
            $this->throwInconsistentSceneException($scene);
        }

        if (null !== $sceneChapter && $this->getBook($chapters) !== $sceneChapter->getBook()) {
            $this->throwInconsistentSceneException($scene);
        }
    }

    private function getChapters(): array
    {
        return array_reduce(
            $this->scenes->toArray(),
            function (array $chapters, Scene $scene): array {
                $chapter = $scene->getChapter();
                if (null !== $chapter && false === in_array($chapter, $chapters, true)) {
                    $chapters[] = $chapter;
                }

                return $chapters;
            },
            []
        );
    }

    private function getBook(array $chapters): ?Book
    {
        return array_reduce(
            $chapters,
            function (?Book $book, Chapter $chapter): ?Book {
                $chapterBook = $chapter->getBook();
                if (null === $book && null !== $chapterBook) {
                    $book = $chapterBook;
                } elseif ($book !== $chapterBook) {
                    // No tests for this, since it would require hacks to create
                    // such a scenario.
                    throw new DomainException(sprintf(
                        'Character "%s" has scenes from at least two different books!',
                        $this->id->toString()
                    ));
                }

                return $book;
            },
            null
        );
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

    private function throwInconsistentSceneException(Scene $scene): void
    {
        throw new DomainException(sprintf(
            'Scene "%s" is inconsistent with other scenes from character "%s"',
            $scene->getId()->toString(),
            $this->id->toString()
        ));
    }
}
