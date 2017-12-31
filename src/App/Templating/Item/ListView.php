<?php

declare(strict_types=1);

namespace App\Templating\Item;

use App\Entity\Scene;
use App\Pagination\Item\ItemPaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var ItemPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, ItemPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\list.html.twig',
                [
                    'items' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            ),
            'page' => $page
        ]);
    }
}
