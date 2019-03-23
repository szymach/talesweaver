<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Traits\CreatedByTrait;
use Talesweaver\Domain\Traits\TimestampableTrait;
use Talesweaver\Domain\Traits\TranslatableTrait;
use Talesweaver\Domain\ValueObject\ShortText;

class Chapter
{
    use CreatedByTrait, TimestampableTrait, TranslatableTrait;

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var Book|null
     */
    private $book;

    /**
     * @var Scene[]|Collection
     */
    private $scenes;

    public function __construct(UuidInterface $id, ShortText $title, ?Book $book, Author $author)
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
        return (string) $this->title;
    }

    /**
     * @param ShortText $title
     * @param Book|null $book
     * @return void
     */
    public function edit(ShortText $title, ?Book $book): void
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

    public function getTitle(): ShortText
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

    public function getScenes(): array
    {
        return $this->scenes->toArray();
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

    private function assertCorrectConstructorData(ShortText $title, Author $author, ?Book $book): void
    {
        if (null !== $book && $author->getId() !== $book->getCreatedBy()->getId()) {
            throw new DomainException(sprintf(
                'Chapter for user "%s" with title "%s" cannot be assigned to book "%s", whose author is "%s"',
                $author->getId()->toString(),
                $title,
                $book->getId()->toString(),
                $book->getCreatedBy()->getId()->toString()
            ));
        }
    }

    private function assertCorrectEditionData(ShortText $title, ?Book $book): void
    {
        if (null !== $book && $this->createdBy->getId() !== $book->getCreatedBy()->getId()) {
            throw new DomainException(sprintf(
                'Chapter for user "%s" with title "%s" cannot be assigned to book "%s", whose author is "%s"',
                $this->createdBy->getId()->toString(),
                $title,
                $book->getId()->toString(),
                $book->getCreatedBy()->getId()->toString()
            ));
        }
    }
}
