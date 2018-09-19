<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use stdClass;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAccessInterface;
use Talesweaver\Integration\Symfony\Bus\Middleware\AuthorAccessMiddleware;
use Talesweaver\Tests\Helper\CallableClass;

class AuthorAccessMiddlewareTest extends TestCase
{
    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $message = new stdClass();

        $this->authorContext->expects($this->never())->method('getAuthor');

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($message);
        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($message, $callable);
    }

    public function testUserAllowed()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAccessInterface::class);
        $message->expects($this->once())->method('isAllowed')->with($author)->willReturn(true);

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($message);

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($message, $callable);
    }

    public function testUserNotAllowedException()
    {
        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->never())->method('__invoke');

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

        $middleware = new AuthorAccessMiddleware($this->authorContext);
        $middleware->handle($message, $callable);
    }

    protected function setUp()
    {
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
