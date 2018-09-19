<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAwareInterface;

class AuthorAwareMiddleware implements MiddlewareInterface
{
    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(AuthorContext $authorContext)
    {
        $this->authorContext = $authorContext;
    }

    public function handle($message, callable $next): void
    {
        if (true === $message instanceof AuthorAwareInterface) {
            $message->setAuthor($this->authorContext->getAuthor());
        }

        $next($message);
    }
}
