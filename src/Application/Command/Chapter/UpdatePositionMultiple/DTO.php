<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Chapter\UpdatePositionMultiple;

use Talesweaver\Domain\Chapter;

final class DTO
{
    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var int
     */
    private $position;

    public function __construct(Chapter $chapter, int $position)
    {
        $this->chapter = $chapter;
        $this->position = $position;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
