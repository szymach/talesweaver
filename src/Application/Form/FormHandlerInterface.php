<?php

declare(strict_types=1);

namespace Talesweaver\Application\Form;

interface FormHandlerInterface
{
    public function isSubmissionValid(): bool;
    public function displayErrors(): bool;
    public function getData();
    public function createView(): FormViewInterface;
}
