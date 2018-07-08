<?php

declare(strict_types=1);

namespace Integration\Controller\Scene;

use Integration\Templating\Scene\ListView;

class ListController
{
    /**
     * @var ListView
     */
    private $templating;

    public function __construct(ListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke($page)
    {
        return $this->templating->createView($page);
    }
}
