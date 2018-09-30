<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Create\DTO as CreateDTO;
use Talesweaver\Application\Command\Scene\Edit\Command;
use Talesweaver\Application\Command\Scene\Edit\DTO as EditDTO;
use Talesweaver\Application\Form\Event\SceneEvents;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
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
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        QueryBus $queryBus,
        AuthorContext $authorContext,
        CommandBus $commandBus,
        HtmlContent $htmlContent
    ) {
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->commandBus = $commandBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->getScene($request->getAttribute('id'));
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new EditDTO($scene),
            ['sceneId' => $scene->getId()]
        );

        if (true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            return $this->responseFactory->toJson([
                'form' => $this->htmlContent->fromTemplate(
                    'scene/form/editForm.html.twig',
                    ['form' => $formHandler->createView()]
                )
            ], false === $formHandler->displayErrors() ? 200 : 403);
        } elseif (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($request, $scene, $formHandler->getData());
        }

        return $this->responseFactory->fromTemplate(
            'scene/editForm.html.twig',
            $this->getViewParameters($request, $scene, $formHandler)
        );
    }

    private function processFormDataAndRedirect(
        ServerRequestInterface $request,
        Scene $scene,
        EditDTO $dto
    ): ResponseInterface {
        $text = $dto->getText();
        $this->commandBus->dispatch(new Command(
            $scene,
            new ShortText($dto->getTitle()),
            null !== $text ? new LongText($text) : null,
            $dto->getChapter()
        ));

        return true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)
            ? $this->responseFactory->toJson(['success' => true])
            : $this->responseFactory->redirectToRoute('scene_edit', ['id' => $scene->getId()])
        ;
    }

    private function getViewParameters(
        ServerRequestInterface $request,
        Scene $scene,
        FormHandlerInterface $formHandler
    ): array {
        $parameters = [
            'form' => $formHandler->createView(),
            'sceneId' => $scene->getId(),
            'title' => $scene->getTitle(),
            'characters' => [],
            'items' => [],
            'locations' => [],
            'events' => [],
            'eventModels' => SceneEvents::getAllEvents()
        ];

        if (null !== $scene->getChapter()) {
            $chapter = $scene->getChapter();
            $parameters['chapterTitle'] = $chapter->getTitle();
            $parameters['chapterId'] = $chapter->getId();
            $parameters['relatedScenes'] = $this->queryBus->query(new ScenesPage($chapter, 1));
            $parameters['nextSceneForm'] = $this->createNextSceneForm($request, $chapter);
        } else {
            $parameters['chapterTitle'] = null;
            $parameters['chapterId'] = null;
            $parameters['relatedScenes'] = [];
        }

        return $parameters;
    }

    private function createNextSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new CreateDTO($chapter)
        )->createView();
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $uuid = Uuid::fromString($id);
        $scene = $this->queryBus->query(new ById($uuid));
        if (false === $scene instanceof Scene
            || $this->authorContext->getAuthor() !== $scene->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No scene for id "%s"!', $uuid->toString()));
        }

        return $scene;
    }
}
