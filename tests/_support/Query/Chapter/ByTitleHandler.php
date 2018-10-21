<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;

class ByTitleHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $books)
    {
        $this->chapters = $books;
    }

    public function __invoke(ByTitle $query): ?Chapter
    {
        return $this->chapters->findOneByTitle($query->getTitle());
    }
}
