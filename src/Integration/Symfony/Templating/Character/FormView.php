<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Character;

use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Zend\Diactoros\Response\JsonResponse;

class FormView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(ResponseFactoryInterface $responseFactory, HtmlContent $htmlContent)
    {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function createView(FormInterface $form, string $title): JsonResponse
    {
        return $this->responseFactory([
            'form' => $this->htmlContent->fromTemplate(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => $title]
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
