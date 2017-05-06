<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\ItemPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListController
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

    public function listAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene,
                    'page' => $page
                ]
            )
        ]);
    }

    public function relatedAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\relatedList.html.twig',
                [
                    'items' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
