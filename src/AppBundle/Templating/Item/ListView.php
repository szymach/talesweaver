<?php

namespace AppBundle\Templating\Item;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Item\ItemPaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ItemPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, ItemPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page) : JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId() : null
                ]
            ),
            'page' => $page
        ]);
    }
}
