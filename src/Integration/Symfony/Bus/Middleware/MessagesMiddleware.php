<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;

class MessagesMiddleware implements MiddlewareInterface
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(FlashBag $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function handle($command, callable $next): void
    {
        $next($command);
        if (false === $command instanceof MessageCommandInterface) {
            return;
        }

        $message = $command->getMessage();
        $this->flashBag->add(new Flash(
            $message->getType(),
            $message->getTranslationKey(),
            $message->getTranslationParameters()
        ));
    }
}
