<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ForBookPaginator;
use AppBundle\Pagination\Chapter\StandalonePaginator;
use AppBundle\Pagination\Chapter\ScenePaginator;
use Pagerfanta\Pagerfanta;

class ChapterAggregate
{
    /**
     * @var ForBookPaginator
     */
    private $forBookPaginator;

    /**
     * @var StandalonePaginator
     */
    private $standalonePaginator;

    /**
     * @var ScenePaginator
     */
    private $scenePaginator;

    public function __construct(
        StandalonePaginator $standalonePaginator,
        ForBookPaginator $forBookPaginator,
        ScenePaginator $scenePaginator
    ) {
        $this->standalonePaginator = $standalonePaginator;
        $this->forBookPaginator = $forBookPaginator;
        $this->scenePaginator = $scenePaginator;
    }

    /**
     * @return Pagerfanta
     */
    public function getStandalone($page)
    {
        return $this->standalonePaginator->getResults($page);
    }

    /**
     * @return Pagerfanta
     */
    public function getForBook(Book $book, $page)
    {
        return $this->forBookPaginator->getForBookResults($book, $page);
    }

    /**
     * @param Chapter $chapter
     * @return Pagerfanta
     */
    public function getScenesForChapter(Chapter $chapter, $page)
    {
        return $this->scenePaginator->getForChapterResults($chapter, $page);
    }
}
