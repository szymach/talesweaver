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
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Symfony\Bus\AuthorAwareBus;
use Talesweaver\Integration\Doctrine\Entity\User;

class AuthorAwareBusTest extends TestCase
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
        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects($this->never())->method('setAuthor');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('No user set when executing command "%s"', get_class($message)));

        $this->messageBus->expects($this->never())->method('handle');

        $bus = new AuthorAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setAuthor'])->getMock();
        $message->expects($this->never())->method('setAuthor');

        $this->tokenStorage->expects($this->never())->method('getToken');
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    public function testSettingUser()
    {
        $author = $this->createMock(Author::class);
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getAuthor')->willReturn($author);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $this->tokenStorage->expects($this->exactly(2))->method('getToken')->willReturn($token);

        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects($this->once())->method('setAuthor')->with($author);
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->tokenStorage);
        $bus->handle($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
    }
}
