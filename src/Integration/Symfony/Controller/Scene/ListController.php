<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Integration\Symfony\Pagination\Scene\ScenePaginator;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(ResponseFactoryInterface $responseFactory, ScenePaginator $pagination)
    {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
    }

    public function __invoke($page)
    {
        return $this->responseFactory->fromTemplate(
            'scene/list.html.twig',
            ['scenes' => $this->pagination->getResults($page)]
        );
    }
}
