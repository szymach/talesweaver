<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Chapter;

class DeleteController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Chapter $chapter,
        int $page
    ): ResponseInterface {
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId(): null;
        $this->commandBus->dispatch(new Command($chapter));

        if (true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return null !== $bookId
            ? $this->responseFactory->redirectToRoute('book_edit', ['id' => $bookId])
            : $this->responseFactory->redirectToRoute('chapter_list', ['page' => $page])
        ;
    }
}
