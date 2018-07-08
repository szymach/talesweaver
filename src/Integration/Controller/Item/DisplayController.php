<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Item;

use Talesweaver\Integration\Templating\Item\DisplayView;
use Talesweaver\Domain\Item;

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
