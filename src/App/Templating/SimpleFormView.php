<?php

declare(strict_types=1);

namespace App\Templating;

use App\Templating\Engine;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class SimpleFormView
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
    {
        $this->templating = $templating;
    }

    public function createView(FormInterface $form, $template, array $fields = []): Response
    {
        $fields['form'] = $form->createView();
        return $this->templating->renderResponse($template, $fields);
    }
}
