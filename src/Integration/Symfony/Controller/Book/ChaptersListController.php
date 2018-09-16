<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Pagination\Book\ChapterPaginator;

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
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        ChapterPaginator $pagination,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->pagination = $pagination;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Book $book, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->pagination->getResults($book, $page, 3),
                    'chapterForm' => $this->createChapterForm($request),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createChapterForm(ServerRequestInterface $request): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest($request, Create::class, new DTO(), [
            'action' => $this->urlGenerator->generate('chapter_create')
        ])->createView();
    }
}
