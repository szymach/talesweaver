<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\MessageBus;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Security\AuthorAwareInterface;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Symfony\Bus\AuthorAwareBus;

class AuthorAwareBusTest extends TestCase
{
    /**
     * @var MessageBus|MockObject
     */
    private $messageBus;

    /**
     * @var AuthorContext|MockObject
     */
    private $authorContext;

    public function testSkippingIncorrectMessageInstance()
    {
        $message = $this->getMockBuilder(stdClass::class)->setMethods(['setAuthor'])->getMock();
        $message->expects($this->never())->method('setAuthor');

        $this->authorContext->expects($this->never())->method('getAuthor');
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->authorContext);
        $bus->handle($message);
    }

    public function testSettingUser()
    {
        $author = $this->createMock(Author::class);
        $this->authorContext->expects($this->once())->method('getAuthor')->willReturn($author);

        $message = $this->createMock(AuthorAwareInterface::class);
        $message->expects($this->once())->method('setAuthor')->with($author);
        $this->messageBus->expects($this->once())->method('handle')->with($message);

        $bus = new AuthorAwareBus($this->messageBus, $this->authorContext);
        $bus->handle($message);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->authorContext = $this->createMock(AuthorContext::class);
    }
}
