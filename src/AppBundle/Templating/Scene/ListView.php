<?php

namespace AppBundle\Templating\Scene;

use AppBundle\Pagination\Scene\ScenePaginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ListView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(EngineInterface $templating, ScenePaginator $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function createView(int $page) : Response
    {
        return $this->templating->renderResponse(
            'scene/list.html.twig',
            ['scenes' => $this->pagination->getResults($page)]
        );
    }
}
