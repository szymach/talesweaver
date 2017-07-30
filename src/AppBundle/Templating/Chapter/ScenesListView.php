<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Form\Scene\CreateType;
use AppBundle\Pagination\Chapter\ScenePaginator;
use AppBundle\Scene\Create\DTO;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
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

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        ScenePaginator $pagination,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createView(Chapter $chapter, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'scenes' => $this->pagination->getResults($chapter, $page),
                    'page' => $page,
                    'sceneForm' => $this->createSceneForm($chapter)->createView()
                ]
            )
        ]);
    }

    private function createSceneForm(Chapter $chapter) : FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('app_scene_create')
        ]);
    }
}
