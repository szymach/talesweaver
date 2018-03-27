<?php

declare(strict_types=1);

namespace App\Templating\Character;

use Domain\Entity\Scene;
use App\Pagination\Character\CharacterPaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var CharacterPaginator
     */
    private $pagination;

    public function __construct(Engine $templating, CharacterPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, int $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null,
                    'page' => $page
                ]
            )
        ]);
    }
}
