<?php

declare(strict_types=1);

namespace App\Templating\Book;

use App\Form\Chapter\CreateType;
use App\Templating\Engine;
use Domain\Chapter\Create\DTO;
use Domain\Entity\Book;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class EditView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Engine $templating,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
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
                'bookId' => $book->getId(),
                'title' => $book->getTitle()
            ]
        );
    }

    private function createChapterForm(Book $book): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($book), [
            'action' => $this->router->generate('chapter_create'),
            'title_placeholder' => 'chapter.placeholder.title.book'
        ]);
    }
}
