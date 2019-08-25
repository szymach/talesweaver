<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Translation;

use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Traits\LocaleTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class ChapterTranslation
{
    use LocaleTrait;

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var ShortText
     */
    private $title;

    /**
     * @var LongText|null
     */
    private $preface;

    /**
     * @var Chapter
     */
    private $chapter;
}
