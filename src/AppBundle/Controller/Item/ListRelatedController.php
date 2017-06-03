<?php

namespace AppBundle\Controller\Item;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\ItemPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListRelatedController
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

    public function __invoke(Scene $scene, $page)
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
