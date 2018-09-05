<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Event;
use Talesweaver\Integration\Symfony\Templating\Event\DisplayView;

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

    public function __invoke(Event $character): ResponseInterface
    {
        return $this->templating->createView($character);
    }
}
