<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Event;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Event\OptionsListView;

class OptionsListController
{
    /**
     * @var OptionsListView
     */
    private $templating;

    public function __construct(OptionsListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Scene $scene): ResponseInterface
    {
        return $this->templating->createView($scene);
    }
}
