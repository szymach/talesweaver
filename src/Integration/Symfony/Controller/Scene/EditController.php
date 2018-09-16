<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Create\DTO as CreateDTO;
use Talesweaver\Application\Scene\Edit\Command;
use Talesweaver\Application\Scene\Edit\DTO as EditDTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;
use Talesweaver\Integration\Symfony\Form\Type\Scene\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Scene\EditType;
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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
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
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        HtmlContent $htmlContent,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        ScenePaginator $scenePaginator,
        EventPaginator $eventPaginator
    ) {
        $this->responseFactory = $responseFactory;
        $this->formFactory = $formFactory;
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
        $form = $this->formFactory->create(EditType::class, new EditDTO($scene), ['sceneId' => $scene->getId()]);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($request, $scene, $form->getData());
        }

        if (true === in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'), true)) {
            return $this->responseFactory->toJson([
                'form' => $this->htmlContent->fromTemplate(
                    'scene/form/editForm.html.twig',
                    ['form' => $form->createView()]
                )
            ], false === $form->isSubmitted() || true === $form->isValid() ? 200 : 403);
        }

        $parameters = [
            'form' => $form->createView(),
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
            $parameters['nextSceneForm'] = $this->createNextSceneForm($chapter);
        } else {
            $parameters['chapterTitle'] = null;
            $parameters['chapterId'] = null;
            $parameters['relatedScenes'] = [];
        }

        return $this->responseFactory->fromTemplate('scene/editForm.html.twig', $parameters);
    }

    private function processFormDataAndRedirect(
        ServerRequestInterface $request,
        Scene $scene,
        EditDTO $dto
    ): ResponseInterface {
        $text = $dto->getText();
        $this->commandBus->handle(new Command(
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

    private function createNextSceneForm(Chapter $chapter): FormView
    {
        return $this->formFactory->create(CreateType::class, new CreateDTO($chapter))->createView();
    }
}
