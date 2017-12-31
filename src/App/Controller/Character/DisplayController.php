<?php

namespace App\Controller\Character;

use App\Entity\Character;
use App\Templating\Character\DisplayView;

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
