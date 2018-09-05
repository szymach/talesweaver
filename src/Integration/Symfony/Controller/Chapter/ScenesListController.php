<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Templating\Chapter\ScenesListView;

class ScenesListController
{
    /**
     * @var ScenesListView
     */
    private $templating;

    public function __construct(ScenesListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Chapter $chapter, int $page): ResponseInterface
    {
        return $this->templating->createView($chapter, $page);
    }
}
