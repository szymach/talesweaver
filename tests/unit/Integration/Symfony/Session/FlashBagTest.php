<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Session;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Integration\Symfony\Session\FlashBag;

class FlashBagTest extends TestCase
{
    /**
     * @var FlashBag
     */
    private $applicationFlashBag;

    /**
     * @var FlashBagInterface|MockObject
     */
    private $flashBag;

    /**
     * @var TranslatorInterface|MockObject
     */
    private $translator;

    public function testMessageAdded(): void
    {
        $this->flashBag->expects(self::exactly(2))->method('add')->withConsecutive(
            ['success', 'success message'],
            ['error', 'an error message']
        );

        $this->flashBag->expects(self::exactly(2))
            ->method('peek')
            ->withConsecutive(['success'], ['error'])
            ->willReturnOnConsecutiveCalls([], [])
        ;

        $this->translator->expects(self::exactly(2))
            ->method('trans')
            ->withConsecutive(
                ['message key', ['1' => '2']],
                ['a key', ['23' => '111']]
            )
            ->willReturnOnConsecutiveCalls('success message', 'an error message')
        ;

        $this->applicationFlashBag->add(new Flash('success', 'message key', ['1' => '2']));
        $this->applicationFlashBag->add(new Flash('error', 'a key', ['23' => '111']));
    }

    public function testDuplicatesNotAdded(): void
    {
        $flash1 = new Flash('success', 'message key', ['1' => '2']);
        $flash2 = new Flash('success', 'message key', ['1' => '2']);

        $this->flashBag->expects(self::once())->method('add')->with('success', 'translated message');
        $this->flashBag->expects(self::exactly(2))
            ->method('peek')
            ->with('success')
            ->willReturnOnConsecutiveCalls([], ['translated message'])
        ;
        $this->translator->expects(self::exactly(2))
            ->method('trans')
            ->with('message key', ['1' => '2'])
            ->willReturn('translated message')
        ;

        $this->applicationFlashBag->add($flash1);
        $this->applicationFlashBag->add($flash2);
    }

    protected function setUp(): void
    {
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);

        $session = $this->createMock(Session::class);
        $session->expects(self::once())->method('getFlashBag')->willReturn($this->flashBag);
        $this->applicationFlashBag = new FlashBag($session, $this->translator);
    }
}
