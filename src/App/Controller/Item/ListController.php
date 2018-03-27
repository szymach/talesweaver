<?php

declare(strict_types=1);

namespace App\Controller\Item;

use App\Templating\Item\ListView;
use Domain\Entity\Scene;

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
