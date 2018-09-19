<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Symfony\Bus\Middleware\AuthorAwareMiddleware;
use Talesweaver\Tests\Helper\CallableClass;

class AuthorAwareMiddlewareTest extends TestCase
{
    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setAuthor'])->getMock();
        $message->expects($this->never())->method('setAuthor');

        $this->authorContext->expects($this->never())->method('getAuthor');

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($message);

        $middleware = new AuthorAwareMiddleware($this->authorContext);
        $middleware->handle($message, $callable);
    }

    public function testSettingUser()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects($this->once())->method('setAuthor')->with($author);

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($message);

        $middleware = new AuthorAwareMiddleware($this->authorContext);
        $middleware->handle($message, $callable);
    }

    protected function setUp()
    {
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
