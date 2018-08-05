<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\ValueObject\Email;

class Author
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Email
     */
    private $email;

    public function __construct(UuidInterface $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
