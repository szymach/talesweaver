<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Book;

use Assert\Assertion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Chapter\Create\Command;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\Type\Book\NextChapter;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\BookResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;

final class ChapterAddController
{
    /**
     * @var BookResolver
     */
    private $bookResolver;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        BookResolver $chapterResolver,
        ApiResponseFactoryInterface $apiResponseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->bookResolver = $chapterResolver;
        $this->apiResponseFactory = $apiResponseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $book = $this->bookResolver->fromRequest($request);
        $formHandler = $this->createFormHandler($request, $book);
        if (true === $formHandler->isSubmissionValid()) {
            return $this->handeFormSubmissionAndReturnSuccessResponse(
                $book,
                $formHandler->getData()['title']
            );
        }

        return $this->apiResponseFactory->form(
            'form/modalContent.html.twig',
            ['form' => $formHandler->createView()],
            $formHandler->displayErrors(),
            'chapter.header.new'
        );
    }

    private function createFormHandler(
        ServerRequestInterface $request,
        Book $book
    ): FormHandlerInterface {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            NextChapter::class,
            null,
            [
                'book' => $book,
                'attr' => [
                    'action' => $this->urlGenerator->generate(
                        'book_add_chapter',
                        ['id' => $book->getId()]
                    ),
                    'class' => 'js-form'
                ]
            ]
        );
    }

    private function handeFormSubmissionAndReturnSuccessResponse(Book $book, ?string $title): ResponseInterface
    {
        Assertion::notNull($title);
        $this->commandBus->dispatch(new Command(Uuid::uuid4(), new ShortText($title), $book));
        return $this->apiResponseFactory->success([]);
    }
}
