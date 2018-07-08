<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Character;

use Talesweaver\Domain\Character;
use Talesweaver\Integration\Templating\Character\DisplayView;

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
