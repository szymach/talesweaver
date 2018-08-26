<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Bus\MessageBus;
use stdClass;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Symfony\Bus\AuthorAccessBus;

class AuthorAccessBusTest extends TestCase
{
    /**
     * @var MessageBus|MockObject
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
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->authorContext);
        $bus->handle($message);
    }

    public function testUserAllowed()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(true);
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAccessBus($this->messageBus, $this->authorContext);
        $bus->handle($message);
    }

    public function testUserNotAllowedException()
    {
        $this->messageBus->expects($this->never())->method('handle');

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
        $bus->handle($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
