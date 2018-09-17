<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Talesweaver\Application\Bus\CommandBus;
use Throwable;

class TransactionWrappedBus implements CommandBus, MessageBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $manager)
    {
        $this->messageBus = $messageBus;
        $this->manager = $manager;
    }

    public function dispatch($message): void
    {
        $this->manager->beginTransaction();
        try {
            $this->messageBus->dispatch($message);
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
