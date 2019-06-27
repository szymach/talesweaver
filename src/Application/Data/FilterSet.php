<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

use Assert\Assertion;
use Countable;
use Iterator;

final class FilterSet implements Countable, Iterator
{
    public const QUERY_KEY = 'filters';
    public const SORT_ASCENDING = 'asc';
    public const SORT_DESCENDING = 'desc';
    public const SORT_CLEAR = 'clear';
    public const SORT_QUERY_KEY = 'sort';

    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @var int
     */
    private $position;

    public function __construct(array $filters)
    {
        Assertion::allIsInstanceOf($filters, Filter::class);
        $this->filters = $filters;
        $this->position = 0;
    }

    final public static function queryKey(): string
    {
        return self::QUERY_KEY;
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function current()
    {
        return $this->filters[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, $this->filters);
    }
}
