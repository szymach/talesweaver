<?php

namespace AppBundle\Templating\Book;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class CreateView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function createView(FormInterface $form): Response
    {
        return $this->templating->renderResponse(
            'book/createForm.html.twig',
            ['form' => $form->createView()]
        );
    }
}
