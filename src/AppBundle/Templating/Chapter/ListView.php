<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Pagination\Chapter\ChapterPaginator;
use Symfony\Component\Templating\EngineInterface;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ChapterPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, ChapterPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView($page)
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getResults($page), 'page' => $page]
        );
    }
}
