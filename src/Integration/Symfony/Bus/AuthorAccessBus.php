<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Security\AuthorAccessInterface;

class AuthorAccessBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(MessageBus $messageBus, AuthorContext $authorContext)
    {
        $this->messageBus = $messageBus;
        $this->authorContext = $authorContext;
    }

    public function handle($message): void
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

        $this->messageBus->handle($message);
    }
}
