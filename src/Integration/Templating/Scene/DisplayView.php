<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating\Scene;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Domain\Scene;

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

    public function createView(Scene $scene): Response
    {
        return $this->templating->renderResponse(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }
}
