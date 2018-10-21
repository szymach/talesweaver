<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;

class TokenByAuthorHandler implements QueryHandlerInterface
{
    /**
     * @var PasswordResetTokens
     */
    private $tokens;

    public function __construct(PasswordResetTokens $tokens)
    {
        $this->tokens = $tokens;
    }

    public function __invoke(TokenByAuthor $query): ?PasswordResetToken
    {
        return $this->tokens->findOneByAuthor($query->getAuthor());
    }
}
