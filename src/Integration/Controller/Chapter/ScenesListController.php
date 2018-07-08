<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Chapter;

use Talesweaver\Integration\Templating\Chapter\ScenesListView;
use Talesweaver\Domain\Chapter;

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
