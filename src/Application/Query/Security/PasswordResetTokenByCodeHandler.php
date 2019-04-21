<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;

final class PasswordResetTokenByCodeHandler implements QueryHandlerInterface
{
    /**
     * @var PasswordResetTokens
     */
    private $passwordResetTokens;

    public function __construct(PasswordResetTokens $passwordResetTokens)
    {
        $this->passwordResetTokens = $passwordResetTokens;
    }

    public function __invoke(PasswordResetTokenByCode $query): ?PasswordResetToken
    {
        return $this->passwordResetTokens->findActiveByCode($query->getCode());
    }
}
