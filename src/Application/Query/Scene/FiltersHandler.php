<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

final class FiltersHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(Filters $query): array
    {
        $chapter = $query->getChapter();
        return [
            'chapter' => [
                'options' => $this->chapters->createListView(null),
                'selected' => null !== $chapter ? $chapter->getId() : null
            ]
        ];
    }
}
