<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

final class TransactionMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->manager->beginTransaction();
        try {
            $response = $stack->next()->handle($envelope, $stack);
            $this->manager->flush();
            $this->manager->commit();
            return $response;
        } catch (Throwable $exception) {
            if (true === $this->manager->getConnection()->isTransactionActive()) {
                $this->manager->rollback();
            }
            throw $exception;
        }
    }
}
