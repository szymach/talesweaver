<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Talesweaver\Domain\Scene;

final class CharactersPage
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
