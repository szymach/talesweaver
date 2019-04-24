<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;

final class AlertsController
{
    /**
     * @var ApiResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ApiResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->keyForTemplate('alerts', 'partial/alerts.html.twig', []);
    }
}
