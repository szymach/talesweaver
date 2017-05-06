<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
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

    public function displayAction(Chapter $chapter)
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
