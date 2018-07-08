<?php

declare(strict_types=1);

namespace Integration\Controller\Scene;

use Integration\Templating\Scene\RelatedListView;
use Domain\Chapter;

class RelatedListController
{
    /**
     * @var RelatedListView
     */
    private $templating;

    public function __construct(RelatedListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Chapter $chapter, int $page)
    {
        return $this->templating->createView($chapter, $page);
    }
}
