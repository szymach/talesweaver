<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Chapter\Create\Command;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Chapter\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormHandlerFactoryInterface $formHandlerFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @ParamConverter("book", class="Talesweaver\Domain\Book", options={"id" = "bookId", "isOptional" = true})
     */
    public function __invoke(ServerRequestInterface $request, ?Book $book): ResponseInterface
    {
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
        $this->commandBus->handle(
            new Command($chapterId, new ShortText($dto->getTitle()), $book)
        );

        return $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapterId]);
    }
}
