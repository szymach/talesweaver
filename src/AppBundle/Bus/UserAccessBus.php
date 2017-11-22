<?php

declare(strict_types=1);

namespace AppBundle\Bus;

use AppBundle\Bus\Traits\UserAccessTrait;
use Domain\Security\UserAccessInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserAccessBus implements MessageBus
{
    use UserAccessTrait;

    /**
     * @var MessageBus
     */
    private $messageBus;

    public function __construct(MessageBus $messageBus, TokenStorageInterface $tokenStorage)
    {
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function handle($message): void
    {
        if ($message instanceof UserAccessInterface) {
            $user = $this->getUser();
            if (!$user) {
                $this->throwNoUserException(get_class($message));
            }

            if (!$message->isAllowed($user)) {
                $this->throwAccessDeniedException(get_class($message));
            }
        }

        $this->messageBus->handle($message);
    }

    /**
     * @param string $class
     * @throws AccessDeniedException
     */
    private function throwAccessDeniedException(string $class): void
    {
        throw new AccessDeniedException(
            sprintf('Access denied to command "%s" for user "%s"', $class)
        );
    }
}
