<?php

declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Security\TokenGenerator;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Scene\Create\DTO;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadIntegrationData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $user = $this->createUser($manager);
        $dto = new DTO();
        $dto->setTitle('Scena');
        $scene = new Scene(
            Uuid::uuid4(),
            $dto,
            $user
        );
        $scene->setLocale($this->container->getParameter('locale'));

        $manager->persist($scene);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function createUser(ObjectManager $manager): User
    {
        $role = new UserRole('ROLE_USER');
        $user = new User(
            'user@example.com',
            password_hash('password', PASSWORD_BCRYPT),
            [$role],
            new TokenGenerator()
        );
        $user->activate();
        $manager->persist($role);
        $manager->persist($user);

        return $user;
    }
}
