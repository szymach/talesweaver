<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Chapter\Create\Command;
use Talesweaver\Application\Chapter\Create\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Templating\SimpleFormView;

class CreateController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @ParamConverter("book", class="Talesweaver\Domain\Book", options={"id" = "bookId", "isOptional" = true})
     */
    public function __invoke(ServerRequestInterface $request, ?Book $book): ResponseInterface
    {
        $bookId = $book ? $book->getId() : null;
        $form = $this->formFactory->create(CreateType::class, new DTO(), ['bookId' => $bookId]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($form->getData(), $book);
        }

        return $this->responseFactory->fromTemplate(
            'chapter/createForm.html.twig',
            ['bookId' => $bookId, 'form' => $form->createView()]
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
