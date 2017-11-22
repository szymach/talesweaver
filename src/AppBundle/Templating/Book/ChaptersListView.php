<?php

declare(strict_types=1);

namespace AppBundle\Templating\Book;

use Domain\Chapter\Create\DTO;
use AppBundle\Entity\Book;
use AppBundle\Form\Chapter\CreateType;
use AppBundle\Pagination\Book\ChapterPaginator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
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

    public function createView(Book $book, $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->pagination->getResults($book, $page),
                    'chapterForm' => $this->createChapterForm($book)->createView(),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createChapterForm(Book $book): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($book), [
            'action' => $this->router->generate('app_chapter_create')
        ]);
    }
}
