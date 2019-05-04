<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Book\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Book\Edit;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Book\ChaptersPage;
use Talesweaver\Application\Query\Book\ScenesPage;
use Talesweaver\Domain\Book;

final class EditController
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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        BookResolver $bookResolver,
        CommandBus $commandBus,
        QueryBus $queryBus,
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator
    ) {
        $this->bookResolver = $bookResolver;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($book),
            ['bookId' => $book->getId()]
        );
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($book, $formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'book/editForm.html.twig',
            [
                'form' => $formHandler->createView(),
                'bookId' => $book->getId(),
                'title' => $book->getTitle(),
                'chapters' => $this->queryBus->query(new ChaptersPage($book, 1)),
                'scenes' => $this->queryBus->query(new ScenesPage($book, 1))
            ]
        );
    }

    private function processFormDataAndRedirect(Book $book, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($book));

        return $this->responseFactory->redirectToRoute('book_edit', ['id' => $book->getId()]);
    }
}
