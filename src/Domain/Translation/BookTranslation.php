<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Translation;

use Talesweaver\Domain\Book;
use Talesweaver\Domain\Traits\LocaleTrait;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class BookTranslation
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
    private $description;

    /**
     * @var Book
     */
    private $book;
}
