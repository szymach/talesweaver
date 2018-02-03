<?php

declare(strict_types=1);

namespace App\Controller\Chapter;

use Domain\Entity\Chapter;
use App\Templating\Chapter\DisplayView;

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
