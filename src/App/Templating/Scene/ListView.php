<?php

declare(strict_types=1);

namespace App\Templating\Scene;

use App\Pagination\Scene\ScenePaginator;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\Response;

class ListView
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(Engine $templating, ScenePaginator $pagination)
    {
        $this->templating = $templating;
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
