<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Mailer\AuthorActionMailer;
use Talesweaver\Domain\Author;

class CreateAuthorHandler
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

    public function handle(CreateAuthor $command)
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
