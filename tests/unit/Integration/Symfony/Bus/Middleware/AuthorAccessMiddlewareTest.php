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

final class AuthorAccessMiddlewareTest extends TestCase
{
    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance(): void
    {
        $envelope = new Envelope(new stdClass());

        $this->authorContext->expects(self::never())->method('getAuthor');

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    public function testUserAllowed(): void
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects(self::once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects(self::once())->method('isAllowed')->with($author)->willReturn(true);

        $envelope = new Envelope($message);
        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    public function testUserNotAllowedException(): void
    {
        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::never())->method('next');

        $author = $this->createMock(Author::class);
        $id = $this->createMock(UuidInterface::class);
        $id->expects(self::once())->method('toString')->willReturn('an uuid');
        $author->expects(self::once())->method('getId')->willReturn($id);
        $this->authorContext->expects(self::once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects(self::once())->method('isAllowed')->with($author)->willReturn(false);
        $envelope = new Envelope($message);

        self::expectException(AccessDeniedException::class);
        self::expectExceptionMessage(sprintf(
            'Access denied to command "%s" for author "an uuid"',
            get_class($message)
        ));

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    protected function setUp(): void
    {
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
