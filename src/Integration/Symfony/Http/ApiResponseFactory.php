<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\HtmlContent;
use Zend\Diactoros\Response\JsonResponse;

class ApiResponseFactory implements ApiResponseFactoryInterface
{
    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(HtmlContent $htmlContent, TranslatorInterface $translator)
    {
        $this->htmlContent = $htmlContent;
        $this->translator = $translator;
    }

    public function success(array $data = []): ResponseInterface
    {
        return $this->jsonResponse($data, 200);
    }

    public function error(array $data = ['error' => 'error']): ResponseInterface
    {
        return $this->jsonResponse($data, 400);
    }

    public function list(string $template, array $parameters, string $title = null): ResponseInterface
    {
        return $this->keyForTemplate('list', $template, $parameters, $title);
    }

    public function form(string $template, array $parameters, bool $displayErrors, string $title = null): ResponseInterface
    {
        return $this->keyForTemplate(
            'form',
            $template,
            $parameters,
            $title,
            false === $displayErrors ? 200 : 400
        );
    }

    public function display(string $template, array $parameters, string $title = null): ResponseInterface
    {
        return $this->keyForTemplate('display', $template, $parameters, $title);
    }

    public function keyForTemplate(
        string $key,
        string $template,
        array $parameters,
        ?string $title,
        int $code = 200
    ): ResponseInterface {
        $body = [$key => $this->htmlContent->fromTemplate($template, $parameters)];
        if (null !== $title) {
            $body['title'] = $this->translator->trans($title);
        }

        return $this->jsonResponse($body, $code);
    }

    private function jsonResponse($data, int $code = 200): JsonResponse
    {
        return new JsonResponse($data, $code);
    }
}
