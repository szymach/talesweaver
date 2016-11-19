<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\StandalonePaginator;
use AppBundle\Pagination\Chapter\ScenePaginator;
use Pagerfanta\Pagerfanta;

class ChapterAggregate
{
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
        ScenePaginator $scenePaginator
    ) {
        $this->standalonePaginator = $standalonePaginator;
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
     * @param Chapter $chapter
     * @return Pagerfanta
     */
    public function getScenesForChapter(Chapter $chapter)
    {
        return $this->scenePaginator->getForChapterResults($chapter);
    }
}
