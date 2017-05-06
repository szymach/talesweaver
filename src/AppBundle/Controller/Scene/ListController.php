<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Pagination\Scene\SceneAggregate;
use Symfony\Component\Templating\EngineInterface;

class ListController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var SceneAggregate
     */
    private $pagination;

    public function __construct(EngineInterface $templating, SceneAggregate $pagination)
    {
        $this->templating = $templating;
        $this->pagination = $pagination;
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'scene/list.html.twig',
            ['scenes' => $this->pagination->getStandalone($page)]
        );
    }
}
