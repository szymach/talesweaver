<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Data\Sortable;
use Talesweaver\Application\Http\ResponseFactoryInterface;

final class SortController
{
    /**
     * @var Sortable
     */
    private $sortable;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(Sortable $sortable, ResponseFactoryInterface $responseFactory)
    {
        $this->sortable = $sortable;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->sortable->setFromRequest($request);
        return $this->responseFactory->redirectToUri($request->getAttribute('redirect'));
    }
}
