<?php

declare(strict_types=1);

namespace App\Templating\Location;

use App\Entity\Scene;
use App\Pagination\Location\RelatedPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Templating\Engine;

class RelatedListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var LocationPaginator
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
                'scene\locations\relatedList.html.twig',
                [
                    'locations' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'sceneTitle' => $scene->getTitle(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            )
        ]);
    }
}
