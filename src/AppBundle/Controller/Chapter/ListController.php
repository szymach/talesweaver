<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Pagination\Chapter\ChapterPaginator;
use Symfony\Component\Templating\EngineInterface;

class ListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ChapterPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ChapterPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function __invoke($page)
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }
}
