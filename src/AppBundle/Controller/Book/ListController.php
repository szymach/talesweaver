<?php

namespace AppBundle\Controller\Book;

use AppBundle\Templating\Book\ListView;

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

    public function listAction($page)
    {
        return $this->templating->createView($page);
    }
}
