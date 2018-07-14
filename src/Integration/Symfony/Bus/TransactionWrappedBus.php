<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus;

use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\Message\Bus\MessageBus;
use Throwable;

class TransactionWrappedBus implements MessageBus
{
    /**
     * @var MessageBus
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(MessageBus $messageBus, EntityManagerInterface $manager)
    {
        $this->messageBus = $messageBus;
        $this->manager = $manager;
    }

    public function handle($message): void
    {
        $this->manager->beginTransaction();
        try {
            $this->messageBus->handle($message);
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
