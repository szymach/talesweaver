<?php

declare(strict_types=1);

namespace Domain\Chapter\Delete;

use App\Entity\Chapter;
use Doctrine\Common\Persistence\ObjectManager;

class CommandHandler
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Command $command): void
    {
        $this->manager->remove(
            $this->manager->getRepository(Chapter::class)->find($command->getId())
        );
        $this->manager->flush();
    }
}
