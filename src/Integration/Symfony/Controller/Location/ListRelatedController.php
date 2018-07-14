<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Location\RelatedListView;

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
