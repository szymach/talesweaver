<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Chapter\Edit;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Chapter\PublicationsPage;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

final class EditController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        ChapterResolver $chapterResolver,
        FormHandlerFactoryInterface $formHandlerFactory,
        UrlGenerator $urlGenerator,
        CommandBus $commandBus,
        QueryBus $queryBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->urlGenerator = $urlGenerator;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $bookId = null !== $chapter->getBook() ? $chapter->getBook()->getId() : null;
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($chapter),
            ['chapterId' => $chapter->getId(), 'bookId' => $bookId]
        );
        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($chapter, $formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'chapter/editForm.html.twig',
            [
                'form' => $formHandler->createView(),
                'chapterId' => $chapter->getId(),
                'bookId' => $bookId,
                'title' => $chapter->getTitle(),
                'scenes' => $this->queryBus->query(new ScenesPage($chapter, 1)),
                'publications' => $this->queryBus->query(new PublicationsPage($chapter, 1)),
            ]
        );
    }

    private function processFormDataAndRedirect(Chapter $chapter, DTO $dto): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($chapter));

        return $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapter->getId()]);
    }
}
