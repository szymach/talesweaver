<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Symfony\Bus\Middleware\AuthorAccessMiddleware;

class AuthorAccessMiddlewareTest extends TestCase
{
    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $envelope = new Envelope(new stdClass());

        $this->authorContext->expects($this->never())->method('getAuthor');

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects($this->once())->method('next')->willReturn($stack);
        $stack->expects($this->once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    public function testUserAllowed()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(true);

        $envelope = new Envelope($message);
        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects($this->once())->method('next')->willReturn($stack);
        $stack->expects($this->once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    public function testUserNotAllowedException()
    {
        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects($this->never())->method('next');

        $author = $this->createMock(Author::class);
        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('an uuid');
        $author->expects($this->once())->method('getId')->willReturn($id);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(false);
        $envelope = new Envelope($message);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage(sprintf(
            'Access denied to command "%s" for author "an uuid"',
            get_class($message)
        ));

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    protected function setUp()
    {
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
