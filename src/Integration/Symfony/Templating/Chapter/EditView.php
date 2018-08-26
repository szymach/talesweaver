<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Chapter;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;

class EditView
{
    /**
     * @var EngineInterface
     */
    private $templating;

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
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createView(FormInterface $form, ?Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/editForm.html.twig',
            [
                'form' => $form->createView(),
                'chapterId' => $chapter->getId(),
                'bookId' => $chapter->getBook() ? $chapter->getBook()->getId() : null,
                'title' => $chapter->getTitle(),
                'sceneForm' => $this->createSceneForm($chapter)->createView()
            ]
        );
    }

    private function createSceneForm(Chapter $chapter): FormInterface
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('scene_create'),
            'title_placeholder' => 'scene.placeholder.title.chapter'
        ]);
    }
}
