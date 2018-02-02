<?php

declare(strict_types=1);

namespace App\Templating\Chapter;

use App\Entity\Chapter;
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

    public function createView(Chapter $chapter): Response
    {
        return $this->templating->renderResponse(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
