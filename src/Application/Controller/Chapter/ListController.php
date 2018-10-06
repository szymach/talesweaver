<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ChaptersPage;

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

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $page = (int) $request->getAttribute('page', 1);
        return $this->responseFactory->fromTemplate(
            'chapter/list.html.twig',
            [
                'chapters' => $this->queryBus->query(new ChaptersPage($page)),
                'page' => $page
            ]
        );
    }
}
