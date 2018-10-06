<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Book\ChaptersPage;

class ChaptersListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var QueryBus
     */
    private $queryBus;

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
        BookResolver $bookResolver,
        HtmlContent $htmlContent,
        QueryBus $queryBus,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->bookResolver = $bookResolver;
        $this->htmlContent = $htmlContent;
        $this->queryBus = $queryBus;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->nullableFromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'book/chapters/list.html.twig',
                [
                    'bookId' => $book->getId(),
                    'chapters' => $this->queryBus->query(new ChaptersPage($book, $page)),
                    'chapterForm' => $this->createChapterForm($request),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createChapterForm(ServerRequestInterface $request): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO(),
            ['action' => $this->urlGenerator->generate('chapter_create')]
        )->createView();
    }
}
