<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Symfony\Bus\Middleware\AuthorAwareMiddleware;

class AuthorAwareMiddlewareTest extends TestCase
{
    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setAuthor'])->getMock();
        $message->expects(self::never())->method('setAuthor');
        $envelope = new Envelope($message);

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $this->authorContext->expects(self::never())->method('getAuthor');

        $middleware = new AuthorAwareMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    public function testSettingUser()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects(self::once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects(self::once())->method('setAuthor')->with($author);

        $envelope = new Envelope($message);

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new AuthorAwareMiddleware($this->authorContext);
        $middleware->handle($envelope, $stack);
    }

    protected function setUp(): void
    {
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
