<?php

declare(strict_types=1);

namespace App\Templating\Item;

use Domain\Entity\Scene;
use App\Pagination\Item\RelatedPaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class RelatedListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var RelatedPaginator
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
