<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Form\Scene\CreateType;
use AppBundle\Pagination\Chapter\ScenePaginator;
use AppBundle\Scene\Create\DTO;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class EditView
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

    public function createView(FormInterface $form, ?Chapter $chapter) : Response
    {
        return $this->templating->renderResponse(
            'chapter/editForm.html.twig',
            [
                'form' => $form->createView(),
                'scenes' => $this->pagination->getResults($chapter, 1),
                'chapterId' => $chapter->getId(),
                'bookId' => $chapter->getBook() ? $chapter->getBook()->getId() : null,
                'title' => $chapter->getTitle($chapter),
                'sceneForm' => $this->createSceneForm($chapter)->createView()
            ]
        );
    }

    private function createSceneForm(Chapter $chapter) : FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('app_scene_create')
        ]);
    }
}
