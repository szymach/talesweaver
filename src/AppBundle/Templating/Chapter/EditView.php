<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Pagination\Chapter\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct(EngineInterface $templating, ScenePaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(FormInterface $form, Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/editForm.html.twig',
            [
                'form' => $form->createView(),
                'scenes' => $this->pagination->getResults($chapter, 1),
                'chapterId' => $chapter->getId(),
                'title' => $chapter->getTitle()
            ]
        );
    }
}
