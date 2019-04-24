<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Book\Delete\Command;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class DeleteController
{
    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        BookResolver $bookResolver,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->bookResolver = $bookResolver;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->commandBus->dispatch(
            new Command($this->bookResolver->fromRequest($request))
        );

        return $this->responseFactory->redirectToRoute(
            'book_list',
            ['page' => $request->getAttribute('page', 1)]
        );
    }
}
