<?php

namespace AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Book\BookAggregate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var BookAggregate
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        BookAggregate $pagination
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'book/list.html.twig',
            ['books' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }

    public function chaptersAction(Book $book, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'book/chapters/list.html.twig',
                [
                    'book' => $book,
                    'chapters' => $this->pagination->getChaptersForBook($book, $page),
                    'page' => $page
                ]
            )
        ]);
    }

}
