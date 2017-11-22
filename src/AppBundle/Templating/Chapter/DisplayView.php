<?php

declare(strict_types=1);

namespace AppBundle\Templating\Chapter;

use AppBundle\Entity\Chapter;
use Symfony\Component\HttpFoundation\Response;
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

    public function createView(Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
