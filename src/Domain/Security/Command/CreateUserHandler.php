<?php

namespace Domain\Security\Command;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Mail\RegistrationMailer;
use AppBundle\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var TokenGenerator
     */
    private $codeGenerator;

    /**
     * @var RegistrationMailer
     */
    private $mailer;

    public function __construct(
        EntityManagerInterface $manager,
        TokenGenerator $codeGenerator,
        RegistrationMailer $mailer
    ) {
        $this->manager = $manager;
        $this->codeGenerator = $codeGenerator;
        $this->mailer = $mailer;
    }

    public function handle(CreateUser $command)
    {
        $role = $this->manager->getRepository(UserRole::class)->findOneBy(
            ['role' => UserRole::USER]
        );
        if (!$role) {
            $role = new UserRole(UserRole::USER);
            $this->manager->persist($role);
        }

        $user = new User(
            $command->getUsername(),
            password_hash($command->getPassword(), PASSWORD_BCRYPT),
            [$role],
            $this->codeGenerator
        );
        $this->manager->persist($user);
        $this->mailer->send($user);
    }
}
