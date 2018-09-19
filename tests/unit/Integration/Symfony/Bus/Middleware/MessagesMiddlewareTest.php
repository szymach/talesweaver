<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;
use Talesweaver\Integration\Symfony\Bus\Middleware\MessagesMiddleware;
use Talesweaver\Tests\Helper\CallableClass;

class MessagesMiddlewareTest extends TestCase
{
    /**
     * @var FlashBag|MockObject
     */
    private $flashBag;

    public function testSkippsWhenUnsupportedMessageInstance()
    {
        $command = $this->getMockBuilder(stdClass::class)->setMethods(['getMessage'])->getMock();
        $command->expects($this->never())->method('getMessage');

        $this->flashBag->expects($this->never())->method('add');

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($command);

        $middleware = new MessagesMiddleware($this->flashBag);
        $middleware->handle($command, $callable);
    }

    public function testSettingFlashMessage()
    {
        $message = $this->createMock(Message::class);
        $message->expects($this->once())->method('getType')->willReturn('success');
        $message->expects($this->once())->method('getTranslationKey')->willReturn('message key');
        $message->expects($this->once())->method('getTranslationParameters')->willReturn([]);

        $command = $this->createMock(MessageCommandInterface::class);
        $command->expects($this->once())->method('getMessage')->willReturn($message);

        $this->flashBag->expects($this->once())->method('add')->with($this->isInstanceOf(Flash::class));

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($command);

        $middleware = new MessagesMiddleware($this->flashBag);
        $middleware->handle($command, $callable);
    }

    protected function setUp()
    {
        $this->flashBag = $this->createMock(FlashBag::class);
    }
}
