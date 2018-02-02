<?php

declare(strict_types=1);

namespace App\Controller\Item;

use App\Entity\Item;
use App\Templating\Item\DisplayView;

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
