<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

final class ChaptersFilterHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(ChaptersFilter $query): array
    {
        return [
            'chapter' => [
                'options' => $this->chapters->createListView(null),
                'selected' => $query->getSelectedId()
            ]
        ];
    }
}
