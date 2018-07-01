<?php

declare(strict_types=1);

namespace App\Controller\Event;

use App\Templating\Event\OptionsListView;
use Domain\Scene;

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
