<?php

declare(strict_types=1);

namespace Integration\Controller\Event;

use Integration\Templating\Event\DisplayView;
use Domain\Event;

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

    public function __invoke(Event $character)
    {
        return $this->templating->createView($character);
    }
}
