<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;

final class MessagesMiddleware implements MiddlewareInterface
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(FlashBag $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $command = $envelope->getMessage();
        $response = $stack->next()->handle($envelope, $stack);
        if (true === $command instanceof MessageCommandInterface && false === $command->isMuted()) {
            $message = $command->getMessage();
            $this->flashBag->add(new Flash(
                $message->getType(),
                $message->getTranslationKey(),
                $message->getTranslationParameters()
            ));
        }

        return $response;
    }
}
