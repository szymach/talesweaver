<?php

declare(strict_types=1);

namespace App\Controller\Location;

use App\Templating\Location\DisplayView;
use Domain\Location;

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
