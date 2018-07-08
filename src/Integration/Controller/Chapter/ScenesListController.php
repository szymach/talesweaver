<?php

declare(strict_types=1);

namespace Integration\Controller\Chapter;

use Integration\Templating\Chapter\ScenesListView;
use Domain\Chapter;

class ScenesListController
{
    /**
     * @var ScenesListView
     */
    private $templating;

    public function __construct(ScenesListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Chapter $chapter, int $page)
    {
        return $this->templating->createView($chapter, $page);
    }
}
