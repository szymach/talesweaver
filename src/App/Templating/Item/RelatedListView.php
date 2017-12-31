<?php

declare(strict_types=1);

namespace App\Templating\Item;

use App\Entity\Scene;
use App\Pagination\Item\RelatedPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Templating\Engine;

class RelatedListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var ItemPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, RelatedPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\items\relatedList.html.twig',
                [
                    'items' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'sceneTitle' => $scene->getTitle(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            )
        ]);
    }
}
