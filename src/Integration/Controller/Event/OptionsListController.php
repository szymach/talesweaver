<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Event;

use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Templating\Event\OptionsListView;

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

    public function __invoke(Scene $scene)
    {
        return $this->templating->createView($scene);
    }
}
