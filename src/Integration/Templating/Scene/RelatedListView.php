<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Scene;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Pagination\Chapter\ScenePaginator;

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
