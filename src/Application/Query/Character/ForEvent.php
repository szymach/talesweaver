<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Talesweaver\Domain\Scene;

final class ForEvent
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
