<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Scene;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Integration\Symfony\Pagination\Scene\ScenePaginator;

class ListView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(ResponseFactoryInterface $responseFactory, ScenePaginator $pagination)
    {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
    }

    public function createView(int $page): Response
    {
        return $this->templating->renderResponse(
            'scene/list.html.twig',
            ['scenes' => $this->pagination->getResults($page)]
        );
    }
}
