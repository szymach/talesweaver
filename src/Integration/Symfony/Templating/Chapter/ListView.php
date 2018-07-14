<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Chapter;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ChapterPaginator;

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

    public function createView($page): Response
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getResults($page), 'page' => $page]
        );
    }
}
