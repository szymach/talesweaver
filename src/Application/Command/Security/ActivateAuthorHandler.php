<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Application\Bus\CommandHandlerInterface;

final class ActivateAuthorHandler implements CommandHandlerInterface
{
    public function __invoke(ActivateAuthor $author): void
    {
        $author->getAuthor()->activate();
    }
}
