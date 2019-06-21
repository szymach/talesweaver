<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Domain\Chapter;

final class Filters
{
    /**
     * @var Chapter|null
     */
    private $chapter;

    public function __construct(?Chapter $chapter)
    {
        $this->chapter = $chapter;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }
}
