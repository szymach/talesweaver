<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Talesweaver\Integration\Symfony\Bus\Middleware\TransactionMiddleware;
use Talesweaver\Tests\Helper\CallableClass;

class TransactionMiddlewareTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $manager;

    public function testNoExceptionThrown()
    {
        $command = new stdClass();

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())->method('__invoke')->with($command);

        $this->manager->expects($this->once())->method('flush');
        $this->manager->expects($this->once())->method('commit');

        $this->manager->expects($this->never())->method('getConnection');
        $this->manager->expects($this->never())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($command, $callable);
    }

    public function testExceptionThrownWithActiveTransaction()
    {
        $this->expectException(Exception::class);

        $command = new stdClass();

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects($this->never())->method('flush');
        $this->manager->expects($this->never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())->method('isTransactionActive')->willReturn(true);

        $this->manager->expects($this->once())->method('getConnection')->willReturn($connection);
        $this->manager->expects($this->once())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($command, $callable);
    }

    public function testExceptionThrownWithoutActiveTransaction()
    {
        $this->expectException(Exception::class);

        $command = new stdClass();

        $callable = $this->createMock(CallableClass::class);
        $callable->expects($this->once())
            ->method('__invoke')
            ->with($command)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects($this->never())->method('flush');
        $this->manager->expects($this->never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())->method('isTransactionActive')->willReturn(false);

        $this->manager->expects($this->once())->method('getConnection')->willReturn($connection);
        $this->manager->expects($this->never())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($command, $callable);
    }

    protected function setUp()
    {
        $this->manager = $this->createMock(EntityManagerInterface::class);
        $this->manager->expects($this->once())->method('beginTransaction');
    }
}
