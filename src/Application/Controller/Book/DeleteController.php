<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Book\Delete\Command;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;

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

    public function __invoke(Book $book, int $page): ResponseInterface
    {
        $this->commandBus->dispatch(new Command($book));

        return $this->responseFactory->redirectToRoute('book_list', ['page' => $page]);
    }
}
