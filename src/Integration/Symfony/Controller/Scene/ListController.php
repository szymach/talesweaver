<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Talesweaver\Integration\Symfony\Templating\Scene\ListView;

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
