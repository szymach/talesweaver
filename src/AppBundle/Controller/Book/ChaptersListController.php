<?php

namespace AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use AppBundle\Templating\Book\ChaptersListView;

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
