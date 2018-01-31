<?php

declare(strict_types=1);

namespace App\Templating\Chapter;

use App\Entity\Chapter;
use App\Form\Scene\CreateType;
use App\Pagination\Chapter\ScenePaginator;
use Domain\Scene\Create\DTO;
use App\Templating\Engine;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class EditView
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

    public function createView(FormInterface $form, ?Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/editForm.html.twig',
            [
                'form' => $form->createView(),
                'scenes' => $this->pagination->getResults($chapter, 1),
                'chapterId' => $chapter->getId(),
                'bookId' => $chapter->getBook() ? $chapter->getBook()->getId(): null,
                'title' => $chapter->getTitle($chapter),
                'sceneForm' => $this->createSceneForm($chapter)->createView()
            ]
        );
    }

    private function createSceneForm(Chapter $chapter): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('scene_create')
        ]);
    }
}