<?php

namespace App\Controller\Location;

use App\Entity\Location;
use App\Templating\Location\DisplayView;

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

    public function __invoke(Location $location)
    {
        return $this->templating->createView($location);
    }
}
