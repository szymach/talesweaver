<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Repository;

interface LatestChangesAwareRepository
{
    /**
     * @param int $limit
     * @return array
     */
    public function findLatest(int $limit = 3): array;
}
