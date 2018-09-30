<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Edit\Command;
use Talesweaver\Application\Command\Chapter\Edit\DTO;
use Talesweaver\Application\Command\Scene;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Chapter\Edit;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator,
        CommandBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->getChapter($request->getAttribute('id'));
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($chapter),
            [
                'chapterId' => $chapter->getId(),
                'bookId' => null !== $chapter->getBook() ? $chapter->getBook()->getId() : null
            ]
        );
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($chapter, $formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'chapter/editForm.html.twig',
            [
                'form' => $formHandler->createView(),
                'chapterId' => $chapter->getId(),
                'bookId' => $this->getBookId($chapter),
                'title' => $chapter->getTitle(),
                'sceneForm' => $this->createSceneForm($request, $chapter)
            ]
        );
    }

    private function processFormDataAndRedirect(Chapter $chapter, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch(new Command(
            $chapter,
            new ShortText($dto->getTitle()),
            $dto->getBook()
        ));

        return $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapter->getId()]);
    }

    private function createSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new Scene\Create\DTO($chapter),
            [
                'action' => $this->urlGenerator->generate('scene_create'),
                'title_placeholder' => 'scene.placeholder.title.chapter'
            ]
        )->createView();
    }

    private function getChapter(?string $id): Chapter
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No chapter id!');
        }

        $uuid = Uuid::fromString($id);
        $chapter = $this->queryBus->query(new ById($uuid));
        if (false === $chapter instanceof Chapter
            || $this->authorContext->getAuthor() !== $chapter->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No chapter for id "%s"!', $uuid->toString()));
        }

        return $chapter;
    }

    private function getBookId(Chapter $chapter): ?UuidInterface
    {
        return null !== $chapter->getBook() ? $chapter->getBook()->getId() : null;
    }
}
