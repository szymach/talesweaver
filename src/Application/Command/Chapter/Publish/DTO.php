<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\Publish;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

final class DTO
{
    /**
     * @var Chapter
     */
    public $chapter;

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var bool
     */
    public $visible;

    public static function fromEntity(Chapter $chapter): self
    {
        $instance = new self;
        $instance->chapter = $chapter;
        $instance->visible = false;

        return $instance;
    }

    public function toCommand(): Command
    {
        if (null !== $this->title && '' !== $this->title) {
            $title = new ShortText($this->title);
        } else {
            $title = $this->chapter->getTitle();
        }

        return new Command($this->chapter, $title, $this->visible);
    }

    private function __construct()
    {
    }
}
