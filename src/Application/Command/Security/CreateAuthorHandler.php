<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Mailer\AuthorActionMailer;
use Talesweaver\Domain\Author;

class CreateAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var AuthorActionMailer
     */
    private $newAuthorMailer;

    public function __construct(EntityManagerInterface $manager, AuthorActionMailer $newAuthorMailer)
    {
        $this->manager = $manager;
        $this->newAuthorMailer = $newAuthorMailer;
    }

    public function __invoke(CreateAuthor $command): void
    {
        $author = new Author(
            Uuid::uuid4(),
            $command->getEmail(),
            $command->getPassword(),
            generate_user_token()
        );

        $this->manager->persist($author);
        $this->newAuthorMailer->send($author);
    }
}
