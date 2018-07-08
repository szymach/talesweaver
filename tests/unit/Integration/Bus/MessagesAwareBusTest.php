<?php

declare(strict_types=1);

namespace Integration\Tests\Bus;

use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Integration\Bus\MessagesAwareBus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\MessageBus;
use stdClass;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class MessagesAwareBusTest extends TestCase
{
    /**
     * @var MessageBus|MockObject
     */
    private $messageBus;

    /**
     * @var Session|MockObject
     */
    private $session;

    /**
     * @var TranslatorInterface|MockObject
     */
    private $translator;

    public function testSkippsWhenUnsupportedMessageInstance()
    {
        $command = $this->getMockBuilder(stdClass::class)->setMethods(['getMessage'])->getMock();
        $command->expects($this->never())->method('getMessage');

        $this->session->expects($this->never())->method('getFlashBag');
        $this->messageBus->expects($this->once())->method('handle')->with($command);

        $bus = new MessagesAwareBus($this->messageBus, $this->session, $this->translator);
        $bus->handle($command);
    }

    public function testSettingFlashMessage()
    {
        $message = $this->createMock(Message::class);
        $message->expects($this->once())->method('getType')->willReturn('message type');
        $message->expects($this->once())->method('getTranslationKey')->willReturn('message key');
        $message->expects($this->once())->method('getTranslationParameters')->willReturn([]);

        $command = $this->createMock(MessageCommandInterface::class);
        $command->expects($this->once())->method('getMessage')->willReturn($message);

        $flashBag = $this->createMock(ParameterBag::class);
        $flashBag->expects($this->once())->method('set')->with('message type', 'translated message');
        $this->session->expects($this->once())->method('getFlashBag')->willReturn($flashBag);
        $this->translator->expects($this->once())
            ->method('trans')
            ->with('message key', [])
            ->willReturn('translated message')
        ;
        $this->messageBus->expects($this->once())->method('handle')->with($command);

        $bus = new MessagesAwareBus($this->messageBus, $this->session, $this->translator);
        $bus->handle($command);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->session = $this->createMock(Session::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
    }
}
