<?php

declare(strict_types=1);

namespace Integration\Templating\Book;

use Domain\Book;
use Integration\Form\Chapter\CreateType;
use Integration\Pagination\Book\ChapterPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Application\Chapter\Create\DTO;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

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

    public function createView(Book $book, int $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->pagination->getResults($book, $page, 3),
                    'chapterForm' => $this->createChapterForm($book)->createView(),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createChapterForm(Book $book): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($book), [
            'action' => $this->router->generate('chapter_create')
        ]);
    }
}
