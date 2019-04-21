<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

final class PasswordResetTokenByCode
{
    /**
     * @var string
     */
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
