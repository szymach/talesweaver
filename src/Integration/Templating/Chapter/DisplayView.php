<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Chapter;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Domain\Chapter;

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
