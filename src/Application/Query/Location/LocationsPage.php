<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Location;

use Talesweaver\Domain\Scene;

class LocationsPage
{
    /**
     * @var Scene
     */
    private $scene;

    /**
     * @var int
     */
    private $page;

    public function __construct(Scene $scene, int $page)
    {
        $this->scene = $scene;
        $this->page = $page;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
