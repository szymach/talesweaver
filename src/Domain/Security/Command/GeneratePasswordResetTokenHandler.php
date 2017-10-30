<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Entity\User;
use AppBundle\Mail\PasswordResetMailer;
use AppBundle\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;

class GeneratePasswordResetTokenHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var PasswordResetMailer
     */
    private $mailer;

    public function __construct(
        EntityManagerInterface $manager,
        TokenGenerator $tokenGenerator,
        PasswordResetMailer $mailer
    ) {
        $this->manager = $manager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public function handle(GeneratePasswordResetToken $command): void
    {
        $user = $this->manager->getRepository(User::class)->findOneBy([
            'username' => $command->getEmail()
        ]);
        $user->addPasswordResetToken($this->tokenGenerator);
        $this->mailer->send($user);
    }
}
