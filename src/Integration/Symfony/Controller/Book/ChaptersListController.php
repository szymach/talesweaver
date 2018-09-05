<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;
use Talesweaver\Integration\Symfony\Http\HtmlContent;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ChapterPaginator;

class ChaptersListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var ChapterPaginator
     */
    private $pagination;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        ChapterPaginator $pagination,
        FormFactoryInterface $formFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->pagination = $pagination;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Book $book, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->pagination->getResults($book, $page, 3),
                    'chapterForm' => $this->createChapterForm($book),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createChapterForm(): FormView
    {
        return $this->formFactory->create(CreateType::class, new DTO(), [
            'action' => $this->router->generate('chapter_create')
        ])->createView();
    }
}
