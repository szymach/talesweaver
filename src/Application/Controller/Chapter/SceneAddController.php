<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Assert\Assertion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Scene\Create\Command;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\Type\Chapter\NextScene;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\ChapterResolver;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

final class SceneAddController
{
    /**
     * @var ChapterResolver
     */
    private $chapterResolver;

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
        ChapterResolver $chapterResolver,
        ApiResponseFactoryInterface $apiResponseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->chapterResolver = $chapterResolver;
        $this->apiResponseFactory = $apiResponseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $chapter = $this->chapterResolver->fromRequest($request);
        $formHandler = $this->createFormHandler($request, $chapter);
        if (true === $formHandler->isSubmissionValid()) {
            $this->createScene($chapter, $formHandler->getData()['title']);
            return $this->apiResponseFactory->success([]);
        }

        return $this->apiResponseFactory->form(
            'form/modalContent.html.twig',
            ['form' => $formHandler->createView()],
            $formHandler->displayErrors(),
            'scene.header.new'
        );
    }

    private function createFormHandler(
        ServerRequestInterface $request,
        Chapter $chapter
    ): FormHandlerInterface {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            NextScene::class,
            null,
            [
                'chapter' => $chapter,
                'attr' => [
                    'action' => $this->urlGenerator->generate(
                        'chapter_add_scene',
                        ['id' => $chapter->getId()]
                    ),
                    'class' => 'js-form'
                ]
            ]
        );
    }

    private function createScene(Chapter $chapter, ?string $title): void
    {
        Assertion::notNull($title);
        $this->commandBus->dispatch(new Command(Uuid::uuid4(), new ShortText($title), $chapter));
    }
}
