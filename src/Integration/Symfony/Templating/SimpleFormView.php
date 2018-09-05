<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class SimpleFormView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function createView(FormInterface $form, $template, array $fields = []): ResponseInterface
    {
        $fields['form'] = $form->createView();
        return $this->responseFactory->fromTemplate($template, $fields);
    }
}
