<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class DisplayController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function displayAction(Scene $scene)
    {
        return $this->templating->renderResponse(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }
}
