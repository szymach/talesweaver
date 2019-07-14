<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use function is_xml_http_request;

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

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        BookResolver $bookResolver,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->bookResolver = $bookResolver;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $parameters = [
            'title' => $book->getTitle(),
            'chapters' => $book->getChapters(),
            'locale' => $book->getLocale()
        ];

        if (true === is_xml_http_request($request)) {
            $response = $this->apiResponseFactory->display('display/modal.html.twig', $parameters);
        } else {
            $response = $this->responseFactory->fromTemplate('display/standalone.html.twig', $parameters);
        }

        return $response;
    }
}
