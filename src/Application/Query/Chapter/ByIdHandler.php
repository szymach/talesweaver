<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;

class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(ById $query): ?Chapter
    {
        return $this->chapters->find($query->getId());
    }
}
