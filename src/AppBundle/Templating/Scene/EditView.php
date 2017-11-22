<?php

declare(strict_types=1);

namespace AppBundle\Templating\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Enum\SceneEvents;
use AppBundle\Pagination\Character\CharacterPaginator;
use AppBundle\Pagination\EventPaginator;
use AppBundle\Pagination\Item\ItemPaginator;
use AppBundle\Pagination\Location\LocationPaginator;
use AppBundle\Pagination\Scene\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class EditView
{
    /**
     * @var EngineInterface
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
        EngineInterface $templating,
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

    public function createView(FormInterface $form, Scene $scene): Response
    {
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
        return $this->templating->renderResponse(
            'scene/editForm.html.twig',
            $parameters
        );
    }
}
