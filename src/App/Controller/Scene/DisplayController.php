<?php

declare(strict_types=1);

namespace App\Controller\Scene;

use App\Templating\Scene\DisplayView;
use Domain\Scene;

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

    public function __invoke(Scene $scene)
    {
        return $this->templating->createView($scene);
    }
}
