<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Location;
use Talesweaver\Integration\Symfony\Templating\Location\DisplayView;

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

    public function __invoke(Location $location): ResponseInterface
    {
        return $this->templating->createView($location);
    }
}
