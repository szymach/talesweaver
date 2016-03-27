<?php

namespace AppBundle\Controller;

use Symfony\Component\Templating\EngineInterface;

/**
 * @author Piotr Szymaszek
 */
class BaseController
{
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
    
    public function indexAction()
    {
        return $this->templating->renderResponse('base\index.html.twig');
    }
}
