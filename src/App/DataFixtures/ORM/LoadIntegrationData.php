<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use App\Entity\Character;
use App\Entity\Scene;
use App\Entity\User;
use App\Entity\UserRole;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Character\Create\DTO as CharacterDTO;
use Domain\Scene\Create\DTO as SceneDTO;
use Ramsey\Uuid\Uuid;

class LoadIntegrationData implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $locale = 'pl';

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
