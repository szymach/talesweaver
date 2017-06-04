<?php

namespace AppBundle\Templating\Book;

use AppBundle\Chapter\Create\DTO;
use AppBundle\Entity\Book;
use AppBundle\Form\Chapter\CreateType;
use AppBundle\Pagination\Book\ChapterPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        ChapterPaginator $pagination,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createView(FormInterface $form, Book $book): Response
    {
        return $this->templating->renderResponse(
            'book/editForm.html.twig',
            [
                'form' => $form->createView(),
                'chapterForm' => $this->createChapterForm($book)->createView(),
                'chapters' => $this->pagination->getResults($book, 1),
                'bookId' => $book->getId(),
                'title' => $book->getTitle()
            ]
        );
    }

    private function createChapterForm(Book $book): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($book), [
            'action' => $this->router->generate('app_chapter_create', ['id' => $book->getId()])
        ]);
    }
}
