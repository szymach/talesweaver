<?php

namespace AppBundle\Templating\Location;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Location\LocationPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, LocationPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page) : JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId() : null
                ]
            ),
            'page' => $page
        ]);
    }
}
