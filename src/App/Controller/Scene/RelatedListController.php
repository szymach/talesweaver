<?php

declare(strict_types=1);

namespace App\Controller\Scene;

use App\Templating\Scene\RelatedListView;
use Domain\Entity\Chapter;

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
