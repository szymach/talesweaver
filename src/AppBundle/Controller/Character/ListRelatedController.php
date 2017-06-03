<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Character\RelatedPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListRelatedController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var CharacterPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, RelatedPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function __invoke(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\relatedList.html.twig',
                [
                    'characters' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
