<?php

declare(strict_types=1);

namespace Integration\Templating;

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

    public function createView(FormInterface $form, $template, array $fields = []): Response
    {
        $fields['form'] = $form->createView();
        return $this->templating->renderResponse($template, $fields);
    }
}
