<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Publication;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Publications;

final class PublicByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Publications
     */
    private $publications;

    public function __construct(Publications $publications)
    {
        $this->publications = $publications;
    }

    public function __invoke(PublicById $query): ?Publication
    {
        return $this->publications->findPublic($query->getId());
    }
}
