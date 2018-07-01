<?php

declare(strict_types=1);

namespace Domain\Translation;

use Domain\Book;
use Domain\Traits\LocaleTrait;

class BookTranslation
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
     * @var string
     */
    private $description;

    /**
     * @var Book
     */
    private $book;
}
