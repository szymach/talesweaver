<?php

declare(strict_types=1);

namespace Integration\Templating\Chapter;

use Integration\Form\Scene\CreateType;
use Integration\Pagination\Chapter\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Domain\Chapter;
use Application\Scene\Create\DTO;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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

    public function createView(Chapter $chapter, int $page): Response
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'scenes' => $this->pagination->getResults($chapter, $page, 3),
                    'page' => $page,
                    'sceneForm' => $this->createSceneForm($chapter)->createView()
                ]
            )
        ]);
    }

    private function createSceneForm(Chapter $chapter): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('scene_create')
        ]);
    }
}
