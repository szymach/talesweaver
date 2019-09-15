<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Mailer\AuthorActionMailer;

final class ResendActivationCodeHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorActionMailer
     */
    private $newAuthorMailer;

    public function __construct(AuthorActionMailer $newAuthorMailer)
    {
        $this->newAuthorMailer = $newAuthorMailer;
    }

    public function __invoke(ResendActivationCode $command): void
    {
        $this->newAuthorMailer->send($command->getAuthor());
    }
}
