<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

class ForBookHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(ForBook $query): array
    {
        return $this->chapters->findForBook($query->getBook());
    }
}
