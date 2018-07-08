<?php

declare(strict_types=1);

namespace Integration\Controller\Item;

use Integration\Templating\Item\DisplayView;
use Domain\Item;

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

    public function __invoke(Item $item)
    {
        return $this->templating->createView($item);
    }
}
