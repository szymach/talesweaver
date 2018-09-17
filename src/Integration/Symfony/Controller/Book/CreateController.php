<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Book;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Book\Create\Command;
use Talesweaver\Application\Book\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Book\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Create::class);
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'book/createForm.html.twig',
            ['form' => $formHandler->createView()]
        );
    }

    private function processFormDataAndRedirect(DTO $dto): ResponseInterface
    {
        $bookId = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($bookId, new ShortText($dto->getTitle())));

        return $this->responseFactory->redirectToRoute('book_edit', ['id' => $bookId]);
    }
}
