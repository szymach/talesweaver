<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Pagination\EventPaginator;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, EventPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(Scene $scene, int $page): JsonResponse
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\events\list.html.twig',
                [
                    'events' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'page' => $page
                ]
            )
        ]);
    }
}
