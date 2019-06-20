<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Bus\Middleware;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Talesweaver\Integration\Symfony\Bus\Middleware\TransactionMiddleware;

class TransactionMiddlewareTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $manager;

    public function testNoExceptionThrown()
    {
        $envelope = new Envelope(new stdClass());

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())->method('handle')->with($envelope, $stack)->willReturn($envelope);

        $this->manager->expects(self::once())->method('flush');
        $this->manager->expects(self::once())->method('commit');

        $this->manager->expects(self::never())->method('getConnection');
        $this->manager->expects(self::never())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($envelope, $stack);
    }

    public function testExceptionThrownWithActiveTransaction()
    {
        self::expectException(Exception::class);

        $envelope = new Envelope(new stdClass());

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())
            ->method('handle')
            ->with($envelope, $stack)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects(self::never())->method('flush');
        $this->manager->expects(self::never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())->method('isTransactionActive')->willReturn(true);

        $this->manager->expects(self::once())->method('getConnection')->willReturn($connection);
        $this->manager->expects(self::once())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($envelope, $stack);
    }

    public function testExceptionThrownWithoutActiveTransaction()
    {
        self::expectException(Exception::class);

        $envelope = new Envelope(new stdClass());

        $stack = $this->createMock(StackMiddleware::class);
        $stack->expects(self::once())->method('next')->willReturn($stack);
        $stack->expects(self::once())
            ->method('handle')
            ->with($envelope, $stack)
            ->will($this->throwException(new Exception()))
        ;

        $this->manager->expects(self::never())->method('flush');
        $this->manager->expects(self::never())->method('commit');

        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())->method('isTransactionActive')->willReturn(false);

        $this->manager->expects(self::once())->method('getConnection')->willReturn($connection);
        $this->manager->expects(self::never())->method('rollback');

        $middleware = new TransactionMiddleware($this->manager);
        $middleware->handle($envelope, $stack);
    }

    protected function setUp(): void
    {
        $this->manager = $this->createMock(EntityManagerInterface::class);
        $this->manager->expects(self::once())->method('beginTransaction');
    }
}
