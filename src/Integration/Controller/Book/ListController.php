<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Book;

use Talesweaver\Integration\Templating\Book\ListView;

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

    public function __invoke(int $page)
    {
        return $this->templating->createView($page);
    }
}
