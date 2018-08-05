<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Doctrine\Entity\User;
use function generate_user_token;

class LoadUserData implements ORMFixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User(
            new Author(Uuid::uuid4(), new Email('user@example.com')),
            password_hash('password', PASSWORD_BCRYPT),
            generate_user_token()
        );
        $user->activate();
        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
