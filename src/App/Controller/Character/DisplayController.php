<?php

declare(strict_types=1);

namespace App\Controller\Character;

use App\Templating\Character\DisplayView;
use Domain\Character;

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

    public function __invoke(Character $character)
    {
        return $this->templating->createView($character);
    }
}
