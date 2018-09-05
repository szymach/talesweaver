<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ResponseFactoryInterface
{
    public function fromTemplate(string $template, array $parameters = []): ResponseInterface;
    public function redirectToRoute(string $route, array $parameters = []): ResponseInterface;
    public function toJson($data, int $code);
    public function accessDenied(string $message, ?Throwable $previous = null): Throwable;
    public function notFound(string $message, ?Throwable $previous = null): Throwable;
}
