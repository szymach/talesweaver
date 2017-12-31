<?php

declare(strict_types=1);

namespace App\Templating\Chapter;

use App\Entity\Chapter;
use Symfony\Component\HttpFoundation\Response;
use App\Templating\Engine;

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

    public function createView(Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
