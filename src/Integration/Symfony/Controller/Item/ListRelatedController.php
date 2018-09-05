<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Templating\Item\RelatedListView;

class ListRelatedController
{
    /**
     * @var RelatedListView
     */
    private $templating;

    public function __construct(RelatedListView $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(Scene $scene, $page): ResponseInterface
    {
        return $this->templating->createView($scene, $page);
    }
}
