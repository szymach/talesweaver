<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Domain\Chapter;

final class PublicationsPage
{
    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var int
     */
    private $page;

    public function __construct(Chapter $chapter, int $page)
    {
        $this->chapter = $chapter;
        $this->page = $page;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
