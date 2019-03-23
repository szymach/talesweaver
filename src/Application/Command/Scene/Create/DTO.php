<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Scene\Create;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(?Chapter $chapter = null)
    {
        $this->chapter = $chapter;
    }

    public function toCommand(UuidInterface $id): Command
    {
        Assertion::notNull($this->title);
        return new Command($id, new ShortText($this->title), $this->chapter);
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
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
