<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Scene;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Enum\SceneEvents;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ScenePaginator;
use Talesweaver\Integration\Symfony\Pagination\Character\CharacterPaginator;
use Talesweaver\Integration\Symfony\Pagination\EventPaginator;
use Talesweaver\Integration\Symfony\Pagination\Item\ItemPaginator;
use Talesweaver\Integration\Symfony\Pagination\Location\LocationPaginator;

class EditView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

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

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        ScenePaginator $scenePaginator,
        EventPaginator $eventPaginator,
        FormFactoryInterface $formFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->characterPaginator = $characterPaginator;
        $this->itemPaginator = $itemPaginator;
        $this->locationPaginator = $locationPaginator;
        $this->scenePaginator = $scenePaginator;
        $this->eventPaginator = $eventPaginator;
        $this->formFactory = $formFactory;
    }

    public function createView(Request $request, FormInterface $form, Scene $scene): Response
    {
        $status = false === $form->isSubmitted() || true === $form->isValid() ? 200 : 403;
        if (true === $request->isXmlHttpRequest()) {
            return new JsonResponse([
                'form' => $this->templating->render(
                    'scene/form/editForm.html.twig',
                    ['form' => $form->createView()]
                )
            ], $status);
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

        return new Response(
            $this->templating->render('scene/editForm.html.twig', $parameters),
            $status
        );
    }

    private function createNextSceneForm(Chapter $chapter): FormView
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter))->createView();
    }
}
