<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class AuthorAccessMiddleware implements MiddlewareInterface
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
        if (true === $message instanceof AuthorAccessInterface) {
            $author = $this->authorContext->getAuthor();
            if (false === $message->isAllowed($author)) {
                throw new AccessDeniedException(sprintf(
                    'Access denied to command "%s" for author "%s"',
                    get_class($message),
                    $author->getId()->toString()
                ));
            }
        }

        $next($message);
    }
}
