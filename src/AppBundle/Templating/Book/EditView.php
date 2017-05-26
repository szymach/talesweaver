<?php

namespace AppBundle\Templating\Book;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Book\ChapterPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class EditView
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

    public function createView(FormInterface $form, Book $book, int $page): Response
    {
        return $this->templating->renderResponse(
            'book/editForm.html.twig',
            [
                'form' => $form->createView(),
                'chapters' => $this->pagination->getResults($book, $page),
                'page' => $page,
                'bookId' => $book->getId(),
                'title' => $book->getTitle()
            ]
        );
    }
}
