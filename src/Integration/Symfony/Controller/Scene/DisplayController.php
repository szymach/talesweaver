<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Scene\DisplayView;

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
