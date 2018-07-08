<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository\Interfaces;

interface LatestChangesAwareRepository
{
    /**
     * @param string $locale
     * @param string $label
     * @param int $limit
     * @return array
     */
    public function findLatest(string $locale, string $label = 'title', int $limit = 5): array;
}
