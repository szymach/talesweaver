<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use stdClass;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;
use Talesweaver\Integration\Symfony\Bus\MessagesAwareBus;

class MessagesAwareBusTest extends TestCase
{
    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBus;

    /**
     * @var FlashBag|MockObject
     */
    private $flashBag;

    public function testSkippsWhenUnsupportedMessageInstance()
    {
        $command = $this->getMockBuilder(stdClass::class)->setMethods(['getMessage'])->getMock();
        $command->expects($this->never())->method('getMessage');

        $this->flashBag->expects($this->never())->method('add');
        $this->messageBus->expects($this->once())->method('dispatch')->with($command);

        $bus = new MessagesAwareBus($this->messageBus, $this->flashBag);
        $bus->dispatch($command);
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
        $this->messageBus->expects($this->once())->method('dispatch')->with($command);

        $bus = new MessagesAwareBus($this->messageBus, $this->flashBag);
        $bus->dispatch($command);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->flashBag = $this->createMock(FlashBag::class);
    }
}
