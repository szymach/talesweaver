<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Domain\Scene;

final class SceneSummary
{
    /**
     * @var Scene
     */
    private $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function getScene(): Scene
    {
        return $this->scene;
    }
}
