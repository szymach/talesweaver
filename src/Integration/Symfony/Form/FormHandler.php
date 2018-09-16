<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Form\FormHandlerInterface;
use Talesweaver\Application\Form\FormViewInterface;

class FormHandler implements FormHandlerInterface
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
        return true == $this->form->isSubmitted() && true === $this->form->isValid();
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
