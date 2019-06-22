<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http;

use Assert\Assertion;
use Countable;
use Iterator;

final class FilterSet implements Countable, Iterator
{
    public const QUERY_KEY = 'filters';

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

    public final function queryKey(): string
    {
        return self::QUERY_KEY;
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function rewind(): void
    {
        $this->position = 0;
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
        ++$this->position;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, $this->filters);
    }
}
