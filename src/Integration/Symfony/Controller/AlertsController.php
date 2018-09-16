<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class AlertsController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(ResponseFactoryInterface $responseFactory, HtmlContent $htmlContent)
    {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'alerts' => $this->htmlContent->fromTemplate('partial/alerts.html.twig', [])
        ]);
    }
}
