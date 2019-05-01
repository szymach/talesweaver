<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

final class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->chapters->entityExists($query->getTitle(), $query->getId(), $query->getBookId());
    }
}
