<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use stdClass;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Symfony\Bus\AuthorAccessBus;

class AuthorAccessBusTest extends TestCase
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
        $message = new stdClass();

        $this->authorContext->expects($this->never())->method('getAuthor');
        $this->messageBus->expects($this->once())->method('dispatch')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->authorContext);
        $bus->dispatch($message);
    }

    public function testUserAllowed()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(true);
        $this->messageBus->expects($this->once())->method('dispatch')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->authorContext);
        $bus->dispatch($message);
    }

    public function testUserNotAllowedException()
    {
        $this->messageBus->expects($this->never())->method('dispatch');

        $author = $this->createMock(Author::class);
        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('an uuid');
        $author->expects($this->once())->method('getId')->willReturn($id);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(false);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage(sprintf(
            'Access denied to command "%s" for author "an uuid"',
            get_class($message)
        ));

        $bus = new AuthorAccessBus($this->messageBus, $this->authorContext);
        $bus->dispatch($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
