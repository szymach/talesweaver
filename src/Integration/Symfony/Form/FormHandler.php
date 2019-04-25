<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\FormViewInterface;

final class FormHandler implements FormHandlerInterface
{
    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(FormInterface $form, ?ServerRequestInterface $request)
    {
        $this->form = $form;
        if (null !== $request) {
            $form->handleRequest($request);
        }
    }

    public function isSubmissionValid(): bool
    {
        return true === $this->form->isSubmitted() && true === $this->form->isValid();
    }

    public function displayErrors(): bool
    {
        return true === $this->form->isSubmitted() && false === $this->form->isValid();
    }

    public function getData()
    {
        return $this->form->getData();
    }

    public function createView(): FormViewInterface
    {
        return new FormView($this->form->createView());
    }
}
