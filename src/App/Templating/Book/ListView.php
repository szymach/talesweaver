<?php

declare(strict_types=1);

namespace App\Templating\Book;

use App\Pagination\Book\BookPaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\Response;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var BookPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, BookPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView($page): Response
    {
        return $this->templating->renderResponse(
            'book/list.html.twig',
            ['books' => $this->pagination->getResults($page)]
        );
    }
}
