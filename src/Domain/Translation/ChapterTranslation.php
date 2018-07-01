<?php

declare(strict_types=1);

namespace Domain\Translation;

use Domain\Chapter;
use Domain\Traits\LocaleTrait;

class ChapterTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Chapter
     */
    private $chapter;
}
