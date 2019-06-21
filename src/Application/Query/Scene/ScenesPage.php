<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Domain\Chapter;

final class ScenesPage
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(int $page, ?Chapter $chapter)
    {
        $this->page = $page;
        $this->chapter = $chapter;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }
}
