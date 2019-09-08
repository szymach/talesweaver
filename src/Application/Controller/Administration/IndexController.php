<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Administration;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class IndexController
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
        return $this->responseFactory->fromTemplate('administration/index.html.twig');
    }
}
