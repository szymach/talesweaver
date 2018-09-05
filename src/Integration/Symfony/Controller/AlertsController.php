<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller;

use Talesweaver\Application\Http\ResponseFactoryInterface;

class AlertsController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke()
    {
        return $this->responseFactory->toJson([
            'alerts' => $this->responseFactory->fr('partial/alerts.html.twig')
        ]);
    }
}
