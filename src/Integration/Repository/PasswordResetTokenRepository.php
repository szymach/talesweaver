<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use DateTimeImmutable;
use Talesweaver\Doctrine\Repository\PasswordResetTokenRepository as DoctrineRepository;

class PasswordResetTokenRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    public function __construct(DoctrineRepository $doctrineRepository)
    {
        $this->doctrineRepository = $doctrineRepository;
    }

    public function findOneByEmail(string $email)
    {
        return $this->doctrineRepository->findOneByEmail($email);
    }

    public function findOneByCode(string $code)
    {
        return $this->doctrineRepository->findOneBy(['value' => $code]);
    }

    public function findCreationDateOfPrevious(string $email): ?DateTimeImmutable
    {
        return $this->doctrineRepository->findCreationDateOfPrevious($email);
    }

    public function deactivatePreviousTokens(string $email): void
    {
        $this->doctrineRepository->deactivatePreviousTokens($email);
    }
}
