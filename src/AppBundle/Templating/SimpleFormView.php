<?php

namespace AppBundle\Templating;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class SimpleFormView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function createView(FormInterface $form, $template): Response
    {
        return $this->templating->renderResponse($template, ['form' => $form->createView()]);
    }
}
