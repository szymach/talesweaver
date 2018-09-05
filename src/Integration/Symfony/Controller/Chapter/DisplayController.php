<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Templating\Chapter\DisplayView;

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

    public function __invoke(Chapter $chapter): ResponseInterface
    {
        return $this->templating->createView($chapter);
    }
}
