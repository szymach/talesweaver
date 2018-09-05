<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Integration\Symfony\Templating\Chapter\ListView;

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

    public function __invoke(int $page): ResponseInterface
    {
        return $this->templating->createView($page);
    }
}
