<?php

declare(strict_types=1);

namespace App\Templating\Chapter;

use App\Pagination\Chapter\ChapterPaginator;
use Symfony\Component\HttpFoundation\Response;
use App\Templating\Engine;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var ChapterPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, ChapterPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView($page): Response
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getResults($page), 'page' => $page]
        );
    }
}
