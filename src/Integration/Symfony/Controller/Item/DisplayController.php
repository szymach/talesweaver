<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Item;
use Talesweaver\Integration\Symfony\Templating\Item\DisplayView;

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

    public function __invoke(Item $item): ResponseInterface
    {
        return $this->templating->createView($item);
    }
}
