<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\CharacterPaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class ListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var CharacterPaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, CharacterPaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function __invoke(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            ),
            'page' => $page
        ]);
    }
}
