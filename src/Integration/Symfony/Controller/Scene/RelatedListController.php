<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Templating\Scene\RelatedListView;

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
