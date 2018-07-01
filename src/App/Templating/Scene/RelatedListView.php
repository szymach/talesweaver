<?php

declare(strict_types=1);

namespace App\Templating\Scene;

use App\Pagination\Chapter\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\Chapter;
use Symfony\Component\HttpFoundation\JsonResponse;

class RelatedListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, ScenePaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Chapter $chapter, int $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene/related/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'chapterTitle' => $chapter->getTitle(),
                    'scenes' => $this->pagination->getResults($chapter, $page, 3),
                    'page' => $page
                ]
            )
        ]);
    }
}
