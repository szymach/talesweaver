<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\LocationPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListRelatedController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, LocationPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function __invoke(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\relatedList.html.twig',
                [
                    'locations' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
