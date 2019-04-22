<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use function generate_user_token;

class LoadUserData extends Fixture implements FixtureGroupInterface
{
    public const AUTHOR = 'author';

    public static function getGroups(): array
    {
        return ['development', 'integration'];
    }

    public function load(ObjectManager $manager)
    {
        $user = new Author(Uuid::uuid4(), new Email('user@example.com'), 'password', generate_user_token());
        $user->activate();
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::AUTHOR, $user);
    }
}
