<?php

declare(strict_types=1);

namespace App\Templating\Character;

use App\Templating\Engine;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormView
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
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
