<?php

namespace AppBundle\Templating\Book;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Book\BookAggregate;
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
     * @var BookAggregate
     */
    private $pagination;

    public function __construct(EngineInterface $templating, BookAggregate $pagination)
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
                'chapters' => $this->pagination->getChaptersForBook($book, $page),
                'page' => $page,
                'bookId' => $book->getId(),
                'title' => $book->getTitle()
            ]
        );
    }
}
