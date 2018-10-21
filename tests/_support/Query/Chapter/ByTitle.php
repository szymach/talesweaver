<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Chapter;

use Talesweaver\Domain\ValueObject\ShortText;

class ByTitle
{
    /**
     * @var ShortText
     */
    private $title;

    public function __construct(ShortText $title)
    {
        $this->title = $title;
    }

    public function getTitle(): ShortText
    {
        return $this->title;
    }
}
