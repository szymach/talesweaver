<?php

namespace AppBundle\Templating\Book;

use AppBundle\Pagination\Book\BookPaginator;
use Symfony\Component\Templating\EngineInterface;

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

    public function createView($page)
    {
        return $this->templating->renderResponse(
            'book/list.html.twig',
            ['books' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }
}
