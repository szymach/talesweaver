<?php

declare(strict_types=1);

namespace App\Templating\Book;

use App\Pagination\Book\BookPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

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
