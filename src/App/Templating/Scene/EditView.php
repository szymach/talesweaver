<?php

declare(strict_types=1);

namespace App\Templating\Scene;

use App\Entity\Scene;
use App\Enum\SceneEvents;
use App\Pagination\Character\CharacterPaginator;
use App\Pagination\EventPaginator;
use App\Pagination\Item\ItemPaginator;
use App\Pagination\Location\LocationPaginator;
use App\Pagination\Scene\ScenePaginator;
use App\Templating\Engine;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditView
{
    /**
     * @var Engine
     */
    private $templating;

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
        Engine $templating,
        ScenePaginator $pagination,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        EventPaginator $eventPaginator
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
        $this->characterPaginator = $characterPaginator;
        $this->itemPaginator = $itemPaginator;
        $this->locationPaginator = $locationPaginator;
        $this->eventPaginator = $eventPaginator;
    }

    public function createView(Request $request, FormInterface $form, Scene $scene): Response
    {
        $status = !$form->isSubmitted() || $form->isValid() ? 200 : 403;
        if ($request->isXmlHttpRequest()) {
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
        if ($scene->getChapter()) {
            $parameters['chapterTitle'] = $scene->getChapter()->getTitle();
            $parameters['chapterId'] = $scene->getChapter()->getId();
            $relatedScenes = [];
            foreach ($scene->getChapter()->getScenes() as $relatedScene) {
                $relatedScenes[] = [
                    'id' => $relatedScene->getId(),
                    'title' => $relatedScene->getTitle()
                ];
            }
            $parameters['relatedScenes'] = $relatedScenes;
        } else {
            $parameters['chapterTitle'] = null;
            $parameters['chapterId'] = null;
            $parameters['relatedScenes'] = [];
        }

        return new Response($this->templating->render(
            'scene/editForm.html.twig',
            $parameters
        ), $status);
    }
}
