<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

class Author
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    public function __construct(UuidInterface $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
