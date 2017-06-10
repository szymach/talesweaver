<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Scene;
use AppBundle\Templating\Location\ListView;

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
