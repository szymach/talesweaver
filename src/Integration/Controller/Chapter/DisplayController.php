<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Chapter;

use Talesweaver\Integration\Templating\Chapter\DisplayView;
use Talesweaver\Domain\Chapter;

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
