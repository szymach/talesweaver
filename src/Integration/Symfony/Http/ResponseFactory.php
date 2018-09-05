<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Throwable;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(HtmlContent $htmlContent, UrlGeneratorInterface $urlGenerator)
    {
        $this->htmlContent = $htmlContent;
        $this->urlGenerator = $urlGenerator;
    }

    public function fromTemplate(string $template, array $parameters = []): ResponseInterface
    {
        return new HtmlResponse($this->htmlContent->fromTemplate($template, $parameters));
    }

    public function redirectToRoute(string $route, array $parameters = []): ResponseInterface
    {
        return new RedirectResponse($this->urlGenerator->generate($route, $parameters));
    }

    public function toJson($data, int $code)
    {
        return new JsonResponse($data, $code);
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
