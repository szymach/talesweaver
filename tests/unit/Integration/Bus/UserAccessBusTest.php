<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SimpleBus\Message\Bus\MessageBus;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Bus\AuthorAccessBus;

class UserAccessBusTest extends TestCase
{
    /**
     * @var MessageBus|MockObject
     */
    private $messageBus;

    /**
     * @var TokenStorageInterface|MockObject
     */
    private $tokenStorage;

    public function testNoUserException()
    {
        $message = $this->createMock(AuthorAccessInterface::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('No user set when executing command "%s"', get_class($message)));

        $this->messageBus->expects($this->never())->method('handle');

        $bus = new AuthorAccessBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testSkippingIncorrectMessageInstance()
    {
        $message = new stdClass();

        $this->tokenStorage->expects($this->never())->method('getToken');
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testUserAllowed()
    {
        $user = $this->createMock(User::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $this->tokenStorage->expects($this->exactly(2))->method('getToken')->willReturn($token);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($user)->willReturn(true);
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testUserNotAllowedException()
    {
        $this->messageBus->expects($this->never())->method('handle');

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getId')->willReturn(1);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $this->tokenStorage->expects($this->exactly(2))->method('getToken')->willReturn($token);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($user)->willReturn(false);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage(sprintf(
            'Access denied to command "%s" for user "1"',
            get_class($message)
        ));

        $bus = new AuthorAccessBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
    }
}
