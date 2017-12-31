<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Templating\Book\ChaptersListView;

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

    public function __invoke(Book $book, $page)
    {
        return $this->templating->createView($book, $page);
    }
}
