<?php

declare(strict_types=1);

namespace Talesweaver\Application\Form;

use Psr\Http\Message\ServerRequestInterface;

interface FormHandlerFactoryInterface
{
    public function createWithRequest(
        ServerRequestInterface $request,
        string $type,
        $data = null,
        array $options = []
    ): FormHandlerInterface;
}
