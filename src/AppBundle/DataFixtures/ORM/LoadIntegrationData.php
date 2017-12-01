<?php

declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Security\TokenGenerator;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Character\Create\DTO as CharacterDTO;
use Domain\Scene\Create\DTO as SceneDTO;
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
        $locale = $this->container->getParameter('locale');

        $user = $this->createUser($manager);
        $sceneDTO = new SceneDTO();
        $sceneDTO->setTitle('Scena');
        $scene = new Scene(Uuid::uuid4(), $sceneDTO, $user);
        $scene->setLocale($locale);

        $characterDTO = new CharacterDTO($scene);
        $characterDTO->setName('Postać do spotkania');
        $character = new Character(Uuid::uuid4(), $characterDTO, $user);
        $character->setLocale($locale);

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
