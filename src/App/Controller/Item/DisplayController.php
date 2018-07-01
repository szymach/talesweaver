<?php

declare(strict_types=1);

namespace App\Controller\Item;

use App\Templating\Item\DisplayView;
use Domain\Entity\Item;

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
