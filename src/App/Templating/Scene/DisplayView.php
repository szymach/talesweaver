<?php

declare(strict_types=1);

namespace App\Templating\Scene;

use Domain\Entity\Scene;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\Response;

class DisplayView
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
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
