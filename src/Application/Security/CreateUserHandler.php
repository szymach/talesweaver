<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\User;
use Talesweaver\Integration\Symfony\Mail\RegistrationMailer;
use function generate_user_token;

class CreateUserHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var RegistrationMailer
     */
    private $mailer;

    public function __construct(EntityManagerInterface $manager, RegistrationMailer $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }

    public function handle(CreateUser $command)
    {
        $user = new User(
            new Author(Uuid::uuid4(), $command->getEmail()),
            $command->getPassword(),
            generate_user_token()
        );

        $this->manager->persist($user);
        $this->mailer->send($user);
    }
}
