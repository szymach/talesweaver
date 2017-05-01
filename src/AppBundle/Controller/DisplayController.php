<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chapter;
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

    public function sceneAction(Scene $scene)
    {
        return $this->templating->renderResponse(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }

    public function chapterAction(Chapter $chapter)
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
