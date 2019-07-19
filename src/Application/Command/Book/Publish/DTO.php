<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Book\Publish;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var Book
     */
    public $book;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var bool
     */
    public $visible;

    public static function fromEntity(Book $book): self
    {
        $instance = new self;
        $instance->book = $book;
        $instance->visible = false;

        return $instance;
    }

    public function toCommand(): Command
    {
        if (null !== $this->title && '' !== $this->title) {
            $title = new ShortText($this->title);
        } else {
            $title = $this->book->getTitle();
        }

        return new Command($this->book, $title, $this->visible);
    }

    private function __construct()
    {
    }
}
