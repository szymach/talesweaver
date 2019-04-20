<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class PdfController
{
    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(BookResolver $bookResolver, ResponseFactoryInterface $responseFactory)
    {
        $this->bookResolver = $bookResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $filename = (string) $book->getTitle();

        return $this->responseFactory->toPdf(
            $filename,
            'book/display.html.twig',
            ['book' => $book],
            null
        );
    }
}
