<?php

declare(strict_types=1);

namespace Integration\Controller\Book;

use Integration\Templating\Book\ChaptersListView;
use Domain\Book;

class ChaptersListController
{
    /**
     * @var ChaptersListView
     */
    private $templating;

    public function __construct(ChaptersListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Book $book, int $page)
    {
        return $this->templating->createView($book, $page);
    }
}
