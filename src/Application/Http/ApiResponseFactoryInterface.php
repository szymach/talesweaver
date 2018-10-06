<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

use Psr\Http\Message\ResponseInterface;

interface ApiResponseFactoryInterface
{
    public function success(array $data = []): ResponseInterface;
    public function error(array $data = ['error' => 'error']): ResponseInterface;
    public function display(string $template, array $parameters): ResponseInterface;
    public function form(string $template, array $parameters, bool $displayErrors): ResponseInterface;
    public function list(string $template, array $parameters): ResponseInterface;
    public function keyForTemplate(
        string $key,
        string $template,
        array $parameters,
        int $code = 200
    ): ResponseInterface;
}
