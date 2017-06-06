<?php

namespace AppBundle\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function createView(FormInterface $form)
    {
        return new JsonResponse([
            'form' => $this->templating->render(
                'partial/simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'event.header.new']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
