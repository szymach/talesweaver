<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ScenesListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ScenePaginator $pagination
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function __invoke(Chapter $chapter, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapter' => $chapter,
                    'scenes' => $this->pagination->getResults($chapter, $page),
                    'page' => $page
                ]
            )
        ]);
    }
}
