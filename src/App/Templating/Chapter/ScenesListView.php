<?php

declare(strict_types=1);

namespace App\Templating\Chapter;

use App\Entity\Chapter;
use App\Form\Scene\CreateType;
use App\Pagination\Chapter\ScenePaginator;
use App\Templating\Engine;
use Domain\Scene\Create\DTO;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class ScenesListView
{
    /**
     * @var Engine
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
        Engine $templating,
        ScenePaginator $pagination,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->pagination = $pagination;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createView(Chapter $chapter, $page): Response
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

    private function createSceneForm(Chapter $chapter): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('scene_create')
        ]);
    }
}
