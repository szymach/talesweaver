<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\ChapterType;
use AppBundle\Pagination\Chapter\ChapterAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class EditController
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
     * @var StandalonePaginator
     */
    private $pagination;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ChapterAggregate $pagination,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->pagination = $pagination;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function editAction(Request $request, Chapter $chapter, $page)
    {
        $form = $this->formFactory->create(ChapterType::class, $chapter);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'chapter/form.html.twig',
            [
                'form' => $form->createView(),
                'chapter' => $chapter,
                'scenes' => $this->pagination->getScenesForChapter($chapter, $page),
                'page' => $page
            ]
        );
    }
}
