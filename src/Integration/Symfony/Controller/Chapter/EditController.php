<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Chapter\Edit\Command;
use Talesweaver\Application\Chapter\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Chapter\Edit;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Scene;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
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

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Chapter $chapter): ResponseInterface
    {
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

    private function getBookId(Chapter $chapter): ?UuidInterface
    {
        return null !==$chapter->getBook() ? $chapter->getBook()->getId() : null;
    }
}
