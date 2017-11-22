<?php

declare(strict_types=1);

namespace AppBundle\Templating\Event;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function createView(FormInterface $form): JsonResponse
    {
        return new JsonResponse([
            'form' => $this->templating->render(
                'partial/simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'event.header.new']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
