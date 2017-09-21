<?php

namespace AppBundle\Security\Command;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handle(CreateUser $command)
    {
        $role = $this->manager->getRepository(UserRole::class)->findOneBy(['role' => UserRole::USER]);
        if (!$role) {
            $role = new UserRole(UserRole::USER);
            $this->manager->persist($role);
        }
        $this->manager->persist(
            new User(
                $command->getUsername(),
                password_hash($command->getPassword(), PASSWORD_BCRYPT),
                [$role]
            )
        );
    }
}
