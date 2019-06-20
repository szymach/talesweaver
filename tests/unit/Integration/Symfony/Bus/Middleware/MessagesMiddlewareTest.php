<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;
use Talesweaver\Integration\Symfony\Bus\Middleware\MessagesMiddleware;

class MessagesMiddlewareTest extends TestCase
{
    /**
     * @var FlashBag|MockObject
     */
    private $flashBag;

    public function testSkippsWhenUnsupportedMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['getMessage'])->getMock();
        $message->expects(self::never())->method('getMessage');
        $envelope = new Envelope($message);

        $this->flashBag->expects(self::never())->method('add');

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new MessagesMiddleware($this->flashBag);
        $middleware->handle($envelope, $stack);
    }

    public function testSettingFlashMessage()
    {
        $message = $this->createMock(Message::class);
        $message->expects(self::once())->method('getType')->willReturn('success');
        $message->expects(self::once())->method('getTranslationKey')->willReturn('message key');
        $message->expects(self::once())->method('getTranslationParameters')->willReturn([]);

        $command = $this->createMock(MessageCommandInterface::class);
        $command->expects(self::once())->method('getMessage')->willReturn($message);

        $envelope = new Envelope($command);

        $this->flashBag->expects(self::once())->method('add')->with(self::isInstanceOf(Flash::class));

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $middleware = new MessagesMiddleware($this->flashBag);
        $middleware->handle($envelope, $stack);
    }

    protected function setUp(): void
    {
        $this->flashBag = $this->createMock(FlashBag::class);
    }
}
