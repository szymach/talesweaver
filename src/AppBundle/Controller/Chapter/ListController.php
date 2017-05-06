<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ChapterAggregate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var StandalonePaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, ChapterAggregate $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }

    public function scenesAction(Chapter $chapter, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapter' => $chapter,
                    'scenes' => $this->pagination->getScenesForChapter($chapter, $page),
                    'page' => $page
                ]
            )
        ]);
    }
}
