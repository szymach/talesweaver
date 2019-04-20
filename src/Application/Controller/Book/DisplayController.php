<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class DisplayController
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
        return $this->responseFactory->fromTemplate(
            'book/display.html.twig',
            ['book' => $this->bookResolver->fromRequest($request)]
        );
    }
}
