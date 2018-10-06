<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Book\ById;
use Talesweaver\Application\Query\Book\ChaptersPage;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;

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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

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
        QueryBus $queryBus,
        AuthorContext $authorContext,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->getBook($request->getAttribute('id'));
        $page = $request->getAttribute('page');
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
        return $this->formHandlerFactory->createWithRequest($request, Create::class, new DTO(), [
            'action' => $this->urlGenerator->generate('chapter_create')
        ])->createView();
    }

    private function getBook(?string $id): Book
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No book id!');
        }

        $uuid = Uuid::fromString($id);
        $book = $this->queryBus->query(new ById($uuid));
        if (false === $book instanceof Book
            || $this->authorContext->getAuthor() !== $book->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No book for id "%s"!', $uuid->toString()));
        }

        return $book;
    }
}
