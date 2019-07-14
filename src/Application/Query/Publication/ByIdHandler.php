<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Publication;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Publications;

final class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Publications
     */
    private $publications;

    public function __construct(Publications $publications)
    {
        $this->publications = $publications;
    }

    public function __invoke(ById $query): ?Publication
    {
        return $this->publications->find($query->getId());
    }
}
