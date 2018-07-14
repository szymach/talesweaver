<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Character\ListView;

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
