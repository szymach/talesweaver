<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Location;

use Talesweaver\Integration\Templating\Location\ListView;
use Talesweaver\Domain\Scene;

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

    public function __invoke(Scene $scene, int $page)
    {
        return $this->templating->createView($scene, $page);
    }
}
