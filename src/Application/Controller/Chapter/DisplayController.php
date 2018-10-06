<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DisplayController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ChapterResolver $chapterResolver, ResponseFactoryInterface $responseFactory)
    {
        $this->chapterResolver = $chapterResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'chapter/display.html.twig',
            ['chapter' => $this->chapterResolver->fromRequest($request)]
        );
    }
}
