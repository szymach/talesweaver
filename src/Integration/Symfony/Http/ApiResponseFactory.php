<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\HtmlContent;
use Zend\Diactoros\Response\JsonResponse;

class ApiResponseFactory implements ApiResponseFactoryInterface
{
    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(HtmlContent $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function success(array $data = []): ResponseInterface
    {
        return $this->jsonResponse($data, 200);
    }

    public function error(array $data = ['error' => 'error']): ResponseInterface
    {
        return $this->jsonResponse($data, 400);
    }

    public function list(string $template, array $parameters): ResponseInterface
    {
        return $this->keyForTemplate('list', $template, $parameters);
    }

    public function form(string $template, array $parameters, bool $displayErrors): ResponseInterface
    {
        return $this->keyForTemplate(
            'form',
            $template,
            $parameters,
            false === $displayErrors ? 200 : 400
        );
    }

    public function display(string $template, array $parameters): ResponseInterface
    {
        return $this->keyForTemplate('display', $template, $parameters);
    }

    public function keyForTemplate(
        string $key,
        string $template,
        array $parameters,
        int $code = 200
    ): ResponseInterface {
        return $this->jsonResponse(
            [$key => $this->htmlContent->fromTemplate($template, $parameters)],
            $code
        );
    }

    private function jsonResponse($data, int $code = 200): JsonResponse
    {
        return new JsonResponse($data, $code);
    }
}
