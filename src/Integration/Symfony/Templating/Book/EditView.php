<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Book;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Form\Chapter\CreateType;

class EditView
{
    /**
     * @var EngineInterface
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
        EngineInterface $templating,
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
        return $this->formFactory->create(CreateType::class, new DTO(), [
            'action' => $this->router->generate('chapter_create', ['bookId' => $book->getId()]),
            'title_placeholder' => 'chapter.placeholder.title.book'
        ]);
    }
}
