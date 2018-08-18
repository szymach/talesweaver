<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository\Interfaces;

interface LatestChangesAwareRepository
{
    /**
     * @param int $limit
     * @return array
     */
    public function findLatest(int $limit = 5): array;
}
