<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Entity\User;
use function generate_user_token;

class LoadUserData implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User('user@example.com', password_hash('password', PASSWORD_BCRYPT), generate_user_token());
        $user->activate();
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
