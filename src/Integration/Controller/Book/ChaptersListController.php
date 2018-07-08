<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Book;

use Talesweaver\Domain\Book;
use Talesweaver\Integration\Templating\Book\ChaptersListView;

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
