<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Administration\Author;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Administration\AllAuthors;

final class ListController
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

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'administration/author.html.twig',
            ['authors' => $this->queryBus->query(new AllAuthors())]
        );
    }
}
