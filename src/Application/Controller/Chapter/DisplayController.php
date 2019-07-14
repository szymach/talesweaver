<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use function is_xml_http_request;

final class DisplayController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        ChapterResolver $chapterResolver,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $parameters = [
            'title' => $chapter->getTitle(),
            'scenes' => $chapter->getScenes(),
            'locale' => $chapter->getLocale()
        ];

        if (true === is_xml_http_request($request)) {
            $response = $this->apiResponseFactory->display('display/modal.html.twig', $parameters);
        } else {
            $response = $this->responseFactory->fromTemplate('display/standalone.html.twig', $parameters);
        }

        return $response;
    }
}
