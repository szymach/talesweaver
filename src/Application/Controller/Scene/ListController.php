<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Scene\ScenesPage;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(ResponseFactoryInterface $responseFactory, QueryBus $queryBus)
    {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
    }

    public function __invoke($page)
    {
        return $this->responseFactory->fromTemplate(
            'scene/list.html.twig',
            ['scenes' => $this->queryBus->query(new ScenesPage($page))]
        );
    }
}
