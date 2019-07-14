<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Exception;
use Knp\Snappy\GeneratorInterface as PdfGenerator;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Throwable;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

final class ResponseFactory implements ResponseFactoryInterface
{
    private const DEFAULT_PDF_OPTIONS = [
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
        'margin-left' => '10mm',
        'margin-right' => '10mm'
    ];

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var PdfGenerator
     */
    private $pdfGenerator;

    public function __construct(
        HtmlContent $htmlContent,
        UrlGeneratorInterface $urlGenerator,
        PdfGenerator $pdfGenerator
    ) {
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function fromTemplate(string $template, array $parameters = []): ResponseInterface
    {
        return new HtmlResponse($this->htmlContent->fromTemplate($template, $parameters));
    }

    public function fromString(string $content): ResponseInterface
    {
        return new HtmlResponse($content);
    }

    public function redirectToRoute(string $route, array $parameters = []): ResponseInterface
    {
        return new RedirectResponse($this->urlGenerator->generate($route, $parameters));
    }

    public function redirectToUri(string $uri): ResponseInterface
    {
        return new RedirectResponse($uri);
    }

    public function toPdf(string $filename, string $template, array $parameters, ?array $options): ResponseInterface
    {
        return new Response(
            $this->pdfGenerator->getOutputFromHtml(
                $this->htmlContent->fromTemplate($template, $parameters),
                $options ?? self::DEFAULT_PDF_OPTIONS
            ),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $filename)
            ]
        );
    }

    public function accessDenied(string $message, ?Throwable $previous = null): Throwable
    {
        return new AccessDeniedException($message, $this->toException($previous));
    }

    public function notFound(string $message, ?Throwable $previous = null): Throwable
    {
        return new NotFoundHttpException($message, $this->toException($previous));
    }

    /**
     * Symfony exceptions accept only objects of class \Exception, so deptract
     * raises an error.
     *
     * @param Throwable $previous
     * @return Exception|null
     */
    private function toException(Throwable $previous = null): ?Exception
    {
        if (null === $previous || true === $previous instanceof Exception) {
            return $previous;
        }

        return new Exception(
            $previous->getMessage(),
            $previous->getCode(),
            $this->toException($previous->getPrevious())
        );
    }
}
