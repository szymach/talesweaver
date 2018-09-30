<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Ramsey\Uuid\UuidInterface;

class ById
{
    /**
     * @var UuidInterface
     */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
