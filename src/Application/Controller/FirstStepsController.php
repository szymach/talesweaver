<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class FirstStepsController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->fromTemplate('firstSteps.html.twig');
    }
}
