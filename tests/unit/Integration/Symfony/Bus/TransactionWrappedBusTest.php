<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use stdClass;
use Talesweaver\Integration\Symfony\Bus\TransactionWrappedBus;

class TransactionWrappedBusTest extends TestCase
{
    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $manager;

    public function testNoExceptionThrown()
    {
        $command = new stdClass();
        $this->messageBus->expects($this->once())->method('dispatch')->with($command);

        $this->manager->expects($this->once())->method('flush');
        $this->manager->expects($this->once())->method('commit');

        $this->manager->expects($this->never())->method('getConnection');
        $this->manager->expects($this->never())->method('rollback');

        $bus = new TransactionWrappedBus($this->messageBus, $this->manager);
        $bus->dispatch($command);
    }

    public function testExceptionThrownWithActiveTransaction()
    {
        $this->expectException(Exception::class);

        $command = new stdClass();
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects($this->never())->method('flush');
        $this->manager->expects($this->never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())->method('isTransactionActive')->willReturn(true);

        $this->manager->expects($this->once())->method('getConnection')->willReturn($connection);
        $this->manager->expects($this->once())->method('rollback');

        $bus = new TransactionWrappedBus($this->messageBus, $this->manager);
        $bus->dispatch($command);
    }

    public function testExceptionThrownWithoutActiveTransaction()
    {
        $this->expectException(Exception::class);

        $command = new stdClass();
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects($this->never())->method('flush');
        $this->manager->expects($this->never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())->method('isTransactionActive')->willReturn(false);

        $this->manager->expects($this->once())->method('getConnection')->willReturn($connection);
        $this->manager->expects($this->never())->method('rollback');

        $bus = new TransactionWrappedBus($this->messageBus, $this->manager);
        $bus->dispatch($command);
    }

    protected function setUp()
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->manager = $this->createMock(EntityManagerInterface::class);
        $this->manager->expects($this->once())->method('beginTransaction');
    }
}
