<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Throwable;

class TransactionMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handle($command, callable $next): void
    {
        $this->manager->beginTransaction();
        try {
            $next($command);
            $this->manager->flush();
            $this->manager->commit();
        } catch (Throwable $exception) {
            if (true === $this->manager->getConnection()->isTransactionActive()) {
                $this->manager->rollback();
            }
            throw $exception;
        }
    }
}
