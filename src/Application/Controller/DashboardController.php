<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\Full;

class DashboardController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(QueryBus $queryBus, ResponseFactoryInterface $responseFactory)
    {
        $this->queryBus = $queryBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'dashboard.html.twig',
            ['timeline' => $this->queryBus->query(new Full())]
        );
    }
}
