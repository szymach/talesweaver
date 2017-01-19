<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ChapterPaginator;
use AppBundle\Pagination\Scene\ScenePaginator;
use Pagerfanta\Pagerfanta;

class ChapterAggregate
{
    /**
     * @var ChapterPaginator
     */
    private $chapterPaginator;

    /**
     * @var ScenePaginator
     */
    private $scenePaginator;

    public function __construct(
        ChapterPaginator $chapterPaginator,
        ScenePaginator $scenePaginator
    ) {
        $this->chapterPaginator = $chapterPaginator;
        $this->scenePaginator = $scenePaginator;
    }

    /**
     * @return Pagerfanta
     */
    public function getStandalone($page)
    {
        return $this->chapterPaginator->getStandalone($page);
    }

    /**
     * @param Chapter $chapter
     * @return Pagerfanta
     */
    public function getScenesForChapter(Chapter $chapter, $page)
    {
        return $this->scenePaginator->getForChapter($chapter, $page);
    }
}
