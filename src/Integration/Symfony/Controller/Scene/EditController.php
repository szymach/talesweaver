<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Command\Scene\Create\DTO as CreateDTO;
use Talesweaver\Application\Command\Scene\Edit\Command;
use Talesweaver\Application\Command\Scene\Edit\DTO as EditDTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ScenePaginator;
use Talesweaver\Integration\Symfony\Pagination\Character\CharacterPaginator;
use Talesweaver\Integration\Symfony\Pagination\EventPaginator;
use Talesweaver\Integration\Symfony\Pagination\Item\ItemPaginator;
use Talesweaver\Integration\Symfony\Pagination\Location\LocationPaginator;

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
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var ScenePaginator
     */
    private $scenePaginator;

    /**
     * @var CharacterPaginator
     */
    private $characterPaginator;

    /**
     * @var ItemPaginator
     */
    private $itemPaginator;

    /**
     * @var LocationPaginator
     */
    private $locationPaginator;

    /**
     * @var EventPaginator
     */
    private $eventPaginator;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        CommandBus $commandBus,
        HtmlContent $htmlContent,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        ScenePaginator $scenePaginator,
        EventPaginator $eventPaginator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->commandBus = $commandBus;
        $this->htmlContent = $htmlContent;
        $this->characterPaginator = $characterPaginator;
        $this->itemPaginator = $itemPaginator;
        $this->locationPaginator = $locationPaginator;
        $this->scenePaginator = $scenePaginator;
        $this->eventPaginator = $eventPaginator;
    }

    public function __invoke(ServerRequestInterface $request, Scene $scene): ResponseInterface
    {
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
            ], true === $formHandler->displayErrors() ? 200 : 403);
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
            'characters' => $this->characterPaginator->getResults($scene, 1),
            'items' => $this->itemPaginator->getResults($scene, 1),
            'locations' => $this->locationPaginator->getResults($scene, 1),
            'events' => $this->eventPaginator->getResults($scene, 1),
            'eventModels' => SceneEvents::getAllEvents()
        ];

        if (null !== $scene->getChapter()) {
            $chapter = $scene->getChapter();
            $parameters['chapterTitle'] = $chapter->getTitle();
            $parameters['chapterId'] = $chapter->getId();
            $parameters['relatedScenes'] = $this->scenePaginator->getResults($chapter, 1);
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
}
