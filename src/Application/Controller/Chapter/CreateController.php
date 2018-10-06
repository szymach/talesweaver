<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Create\Command;
use Talesweaver\Application\Command\Chapter\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

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
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->bookResolver = $bookResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->nullableFromRequest($request);
        $bookId = $book ? $book->getId() : null;
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO(),
            ['bookId' => $bookId]
        );
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($formHandler->getData(), $book);
        }

        return $this->responseFactory->fromTemplate(
            'chapter/createForm.html.twig',
            ['bookId' => $bookId, 'form' => $formHandler->createView()]
        );
    }

    private function processFormDataAndRedirect(DTO $dto, ?Book $book): ResponseInterface
    {
        $chapterId = Uuid::uuid4();
        $this->commandBus->dispatch(
            new Command($chapterId, new ShortText($dto->getTitle()), $book)
        );

        return $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapterId]);
    }
}
