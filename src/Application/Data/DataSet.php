<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

use Assert\Assertion;
use Pagerfanta\Pagerfanta;

final class DataSet
{
    /**
     * @var Header[]
     */
    private $headers;

    /**
     * @var Pagerfanta
     */
    private $paginator;

    public function __construct(array $headers, Pagerfanta $paginator)
    {
        Assertion::allIsInstanceOf($headers, Header::class);

        $this->headers = $headers;
        $this->paginator = $paginator;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPaginator(): Pagerfanta
    {
        return $this->paginator;
    }
}
