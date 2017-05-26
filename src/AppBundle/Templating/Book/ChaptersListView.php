<?php

namespace AppBundle\Templating\Book;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Book\ChapterPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ChaptersListView
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

    public function createView(Book $book, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->pagination->getResults($book, $page),
                    'page' => $page
                ]
            )
        ]);
    }
}
