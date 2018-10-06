<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Delete\Command;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DeleteController
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

    public function __construct(
        ChapterResolver $chapterResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId(): null;
        $this->commandBus->dispatch(new Command($chapter));

        if (true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return null !== $bookId
            ? $this->responseFactory->redirectToRoute('book_edit', ['id' => $bookId])
            : $this->responseFactory->redirectToRoute(
                'chapter_list',
                ['page' => $request->getAttribute('page')]
            )
        ;
    }
}
