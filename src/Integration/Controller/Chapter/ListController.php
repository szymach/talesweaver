<?php

declare(strict_types=1);

namespace Integration\Controller\Chapter;

use Integration\Templating\Chapter\ListView;

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
