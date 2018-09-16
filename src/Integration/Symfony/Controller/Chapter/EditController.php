<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Talesweaver\Application\Chapter\Edit\Command;
use Talesweaver\Application\Chapter\Edit\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Chapter\EditType;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;

class EditController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request, Chapter $chapter): ResponseInterface
    {
        $form = $this->formFactory->create(EditType::class, new DTO($chapter), [
            'chapterId' => $chapter->getId(),
            'bookId' => null !== $chapter->getBook() ? $chapter->getBook()->getId() : null
        ]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($chapter, $form->getData());
        }

        return $this->responseFactory->fromTemplate(
            'chapter/editForm.html.twig',
            [
                'form' => $form->createView(),
                'chapterId' => $chapter->getId(),
                'bookId' => $chapter->getBook() ? $chapter->getBook()->getId() : null,
                'title' => $chapter->getTitle(),
                'sceneForm' => $this->createSceneForm($chapter)
            ]
        );
    }

    private function processFormDataAndRedirect(Chapter $chapter, DTO $dto): ResponseInterface
    {
        $this->commandBus->handle(new Command(
            $chapter,
            new ShortText($dto->getTitle()),
            $dto->getBook()
        ));

        return $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapter->getId()]);
    }

    private function createSceneForm(Chapter $chapter): FormView
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->urlGenerator->generate('scene_create'),
            'title_placeholder' => 'scene.placeholder.title.chapter'
        ])->createView();
    }
}
