<?php

declare(strict_types=1);

namespace App\Controller\Chapter;

use App\Entity\Chapter;
use App\Templating\Chapter\ScenesListView;

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

    public function __invoke(Chapter $chapter, $page)
    {
        return $this->templating->createView($chapter, $page);
    }
}
