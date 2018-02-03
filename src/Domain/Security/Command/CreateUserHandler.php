<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use Domain\Entity\User;
use App\Mail\RegistrationMailer;
use Doctrine\ORM\EntityManagerInterface;
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
            $command->getUsername(),
            password_hash($command->getPassword(), PASSWORD_BCRYPT),
            generate_user_token()
        );

        $this->manager->persist($user);
        $this->mailer->send($user);
    }
}
