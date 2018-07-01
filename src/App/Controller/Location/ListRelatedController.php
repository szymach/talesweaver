<?php

declare(strict_types=1);

namespace App\Controller\Location;

use App\Templating\Location\RelatedListView;
use Domain\Scene;

class ListRelatedController
{
    /**
     * @var RelatedListView
     */
    private $templating;

    public function __construct(RelatedListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Scene $scene, $page)
    {
        return $this->templating->createView($scene, $page);
    }
}
