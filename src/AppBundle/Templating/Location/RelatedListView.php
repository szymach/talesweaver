<?php

namespace AppBundle\Templating\Location;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Location\RelatedPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class RelatedListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, RelatedPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page) : JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\relatedList.html.twig',
                [
                    'locations' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'sceneTitle' => $scene->getTitle(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId() : null
                ]
            )
        ]);
    }
}
