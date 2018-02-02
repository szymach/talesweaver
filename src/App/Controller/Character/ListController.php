<?php

declare(strict_types=1);

namespace App\Controller\Character;

use App\Entity\Scene;
use App\Templating\Character\ListView;

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

    public function __invoke(Scene $scene, $page)
    {
        return $this->templating->createView($scene, $page);
    }
}
