<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Domain\ValueObject\Email;

class AuthorByEmail
{
    /**
     * @var Email
     */
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
