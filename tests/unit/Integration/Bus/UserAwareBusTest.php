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
use Talesweaver\Domain\Security\UserAwareInterface;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Bus\UserAwareBus;

class UserAwareBusTest extends TestCase
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
        $message = $this->createMock(UserAwareInterface::class);
        $message->expects($this->never())->method('setUser');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('No user set when executing command "%s"', get_class($message)));

        $this->messageBus->expects($this->never())->method('handle');

        $bus = new UserAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setUser'])->getMock();
        $message->expects($this->never())->method('setUser');

        $this->tokenStorage->expects($this->never())->method('getToken');
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new UserAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testSettingUser()
    {
        $user = $this->createMock(User::class);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $this->tokenStorage->expects($this->exactly(2))->method('getToken')->willReturn($token);

        $message = $this->createMock(UserAwareInterface::class);
        $message->expects($this->once())->method('setUser')->with($user);
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new UserAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
    }
}
