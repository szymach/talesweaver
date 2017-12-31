<?php

declare(strict_types=1);

namespace App\Templating\Character;

use App\Entity\Scene;
use App\Pagination\Character\RelatedPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Templating\Engine;

class RelatedListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var CharacterPaginator
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
                'scene\characters\relatedList.html.twig',
                [
                    'characters' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'sceneTitle' => $scene->getTitle(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            )
        ]);
    }
}
