<?php

declare(strict_types=1);

namespace Integration\Templating\Scene;

use Integration\Enum\SceneEvents;
use Integration\Form\Scene\CreateType;
use Integration\Pagination\Chapter\ScenePaginator;
use Integration\Pagination\Character\CharacterPaginator;
use Integration\Pagination\EventPaginator;
use Integration\Pagination\Item\ItemPaginator;
use Integration\Pagination\Location\LocationPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\Chapter;
use Domain\Scene;
use Application\Scene\Create\DTO;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditView
{
    /**
     * @var EngineInterface
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
        EngineInterface $templating,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        ScenePaginator $scenePaginator,
        EventPaginator $eventPaginator,
        FormFactoryInterface $formFactory
    ) {
        $this->templating = $templating;
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
