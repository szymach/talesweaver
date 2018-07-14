<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Character;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Pagination\Character\RelatedPaginator;

class RelatedListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var RelatedPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, RelatedPaginator $pagination)
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
