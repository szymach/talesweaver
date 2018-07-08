<?php

declare(strict_types=1);

namespace Integration\Controller\Chapter;

use Integration\Templating\Chapter\DisplayView;
use Domain\Chapter;

class DisplayController
{
    /**
     * @var DisplayView
     */
    private $templating;

    public function __construct(DisplayView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Chapter $chapter)
    {
        return $this->templating->createView($chapter);
    }
}
