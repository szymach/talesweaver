<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Location\ListView;

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

    public function __invoke(Scene $scene, int $page): ResponseInterface
    {
        return $this->templating->createView($scene, $page);
    }
}
