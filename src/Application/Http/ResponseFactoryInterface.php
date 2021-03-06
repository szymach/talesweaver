<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ResponseFactoryInterface
{
    public function fromTemplate(string $template, array $parameters = []): ResponseInterface;
    public function fromString(string $content): ResponseInterface;
    public function redirectToRoute(string $route, array $parameters = []): ResponseInterface;
    public function redirectToUri(string $uri): ResponseInterface;
    public function toPdf(string $filename, string $template, array $parameters, ?array $options): ResponseInterface;
    public function accessDenied(string $message, ?Throwable $previous = null): Throwable;
    public function notFound(string $message, ?Throwable $previous = null): Throwable;
}
