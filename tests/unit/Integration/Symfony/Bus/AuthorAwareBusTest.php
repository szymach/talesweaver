<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Domain\User;
use Talesweaver\Integration\Symfony\Bus\AuthorAwareBus;

class AuthorAwareBusTest extends TestCase
{
    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBus;

    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setAuthor'])->getMock();
        $message->expects($this->never())->method('setAuthor');

        $this->authorContext->expects($this->never())->method('getAuthor');
        $this->messageBus->expects($this->once())->method('dispatch')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->authorContext);
        $bus->dispatch($message);
    }

    public function testSettingUser()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects($this->once())->method('setAuthor')->with($author);
        $this->messageBus->expects($this->once())->method('dispatch')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->authorContext);
        $bus->dispatch($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
