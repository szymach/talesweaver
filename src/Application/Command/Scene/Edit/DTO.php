<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Edit;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(Scene $scene)
    {
        $this->id = $scene->getId();
        $this->title = (string) $scene->getTitle();
        $this->text = null !== $scene->getText() ? (string) $scene->getText() : null;
        $this->chapter = $scene->getChapter();
    }

    public function toCommand(Scene $scene): Command
    {
        Assertion::notNull($this->title);
        return new Command(
            $scene,
            new ShortText($this->title),
            LongText::fromNullableString($this->text),
            $this->chapter
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): void
    {
        $this->chapter = $chapter;
    }
}
