<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAwareInterface;

final class AuthorAwareMiddleware implements MiddlewareInterface
{
    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(AuthorContext $authorContext)
    {
        $this->authorContext = $authorContext;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if (true === $message instanceof AuthorAwareInterface) {
            $message->setAuthor($this->authorContext->getAuthor());
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
