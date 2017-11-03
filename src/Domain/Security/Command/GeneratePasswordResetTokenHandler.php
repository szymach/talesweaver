<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Mail\PasswordResetMailer;
use AppBundle\Repository\PasswordResetTokenRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Security\TokenGenerator;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class GeneratePasswordResetTokenHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var PasswordResetTokenRepository
     */
    private $tokenRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

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
        PasswordResetTokenRepository $tokenRepository,
        UserRepository $userRepository,
        TokenGenerator $tokenGenerator,
        PasswordResetMailer $mailer
    ) {
        $this->manager = $manager;
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public function handle(GeneratePasswordResetToken $command): void
    {
        $email = $command->getEmail();
        if ($this->isRequestTooSoon($email)) {
            return;
        }

        $this->tokenRepository->deactivatePreviousTokens($email);

        $user = $this->userRepository->findOneByUsername($email);
        $user->addPasswordResetToken($this->tokenGenerator);
        $this->mailer->send($user);
    }

    private function isRequestTooSoon(string $email): bool
    {
        $previousTokenDate = $this->tokenRepository->findCreationDateOfPrevious($email);
        if (!$previousTokenDate) {
            return false;
        }

        /* @var $diff DateInterval */
        $diff = $previousTokenDate->diff(new DateTimeImmutable());
        if ($diff->days >= 1) {
            return false;
        }

        if ($diff->h >= 1 || $diff->i >= 5) {
            return false;
        }

        return true;
    }
}
