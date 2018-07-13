<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Assert\Assertion;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Ramsey\Uuid\UuidInterface;

class Chapter
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

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

    public function __construct(UuidInterface $id, string $title, ?Book $book, Author $author)
    {
        $this->assertCorrectConstructorData($title, $author, $book);

        $this->id = $id;
        $this->title = $title;
        $this->book = $book;
        $this->scenes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->createdBy = $author;
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @param Book|null $book
     * @return void
     */
    public function edit(string $title, ?Book $book): void
    {
        $this->assertCorrectEditionData($title, $book);

        $this->title = $title;
        $this->book = $book;

        $this->update();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scene $scene): void
    {
        if (true === $this->scenes->contains($scene)) {
            return;
        }

        $this->scenes->add($scene);
    }

    public function removeScene(Scene $scene): void
    {
        $this->scenes->removeElement($scene);
    }

    private function assertCorrectConstructorData(string $title, Author $author, ?Book $book): void
    {
        Assertion::notBlank($title, sprintf(
            'Cannot create a chapter without a title for author "%s"!',
            $author->getUsername()
        ));

        if (null !== $book) {
            Assertion::eq(
                $author->getId(),
                $book->getCreatedBy()->getId(),
                sprintf(
                    'Chapter for user "%s" with title "%s" cannot be assigned to book "%s", whose author is "%s"',
                    $author->getId(),
                    $title,
                    $book->getId()->toString(),
                    $book->getCreatedBy()->getId()
                )
            );
        }
    }

    private function assertCorrectEditionData(string $title, ?Book $book): void
    {
        Assertion::notBlank($title, sprintf(
            'Tried to set an empty title on chapter with id "%s"!',
            $this->id->toString()
        ));

        if (null !== $book) {
            Assertion::eq(
                $this->createdBy,
                $book->getCreatedBy(),
                sprintf(
                    'Chapter for user "%s" with title "%s" cannot be assigned to book "%s", whose author is "%s"',
                    $this->createdBy->getId(),
                    $title,
                    $book->getId()->toString(),
                    $book->getCreatedBy()->getId()
                )
            );
        }
    }
}
