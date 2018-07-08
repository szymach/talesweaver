<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Book;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Integration\Pagination\Book\BookPaginator;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var BookPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, BookPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(int $page): Response
    {
        return $this->templating->renderResponse(
            'book/list.html.twig',
            ['books' => $this->pagination->getResults($page)]
        );
    }
}
