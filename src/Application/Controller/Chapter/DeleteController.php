<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Delete\Command;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class DeleteController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

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
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $bookId = null !== $chapter->getBook() ? $chapter->getBook()->getId(): null;
        $this->commandBus->dispatch(new Command($chapter));

        if (true === is_xml_http_request($request)) {
            return $this->apiResponseFactory->success();
        }

        return null !== $bookId
            ? $this->responseFactory->redirectToRoute('book_edit', ['id' => $bookId])
            : $this->responseFactory->redirectToRoute(
                'chapter_list',
                ['page' => $request->getAttribute('page', 1)]
            )
        ;
    }
}
