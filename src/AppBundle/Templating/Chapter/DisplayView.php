<?php

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use Symfony\Component\Templating\EngineInterface;

class DisplayView
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function createView(Chapter $chapter)
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
