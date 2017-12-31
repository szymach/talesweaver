<?php

declare(strict_types=1);

namespace App\Templating\Location;

use App\Entity\Scene;
use App\Pagination\Location\LocationPaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, LocationPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            ),
            'page' => $page
        ]);
    }
}
