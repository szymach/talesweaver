<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Book\ScenesPage;

final class ScenesListController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var BookResolver
     */
    private $bookResolver;

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
        ApiResponseFactoryInterface $responseFactory,
        BookResolver $bookResolver,
        QueryBus $queryBus,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->bookResolver = $bookResolver;
        $this->queryBus = $queryBus;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->list(
            'book/tab/scenes.html.twig',
            [
                'bookId' => $book->getId(),
                'scenes' => $this->queryBus->query(new ScenesPage($book, $page)),
                'page' => $page
            ]
        );
    }
}
