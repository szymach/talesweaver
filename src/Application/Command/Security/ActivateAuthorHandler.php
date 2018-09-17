<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

class ActivateAuthorHandler
{
    public function handle(ActivateAuthor $author)
    {
        $author->getAuthor()->activate();
    }
}
