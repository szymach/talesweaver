<?php

declare(strict_types=1);

namespace AppBundle\Templating\Item;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

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

    public function createView(FormInterface $form, string $title): JsonResponse
    {
        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => $title]
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
