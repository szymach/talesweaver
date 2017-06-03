<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ScenePaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ScenesListView
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

    public function createView(Chapter $chapter, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'chapters' => $this->pagination->getResults($chapter, $page),
                    'page' => $page
                ]
            )
        ]);
    }
}
