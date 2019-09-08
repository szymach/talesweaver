<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\ShortText;
use function generate_user_token;

final class LoadUserData extends Fixture implements FixtureGroupInterface
{
    public const AUTHOR = 'author';

    public static function getGroups(): array
    {
        return ['development', 'integration'];
    }

    public function load(ObjectManager $manager)
    {
        $user = new Author(
            Uuid::uuid4(),
            new Email('user@example.com'),
            'password',
            generate_user_token(),
            new ShortText('Imię'),
            new ShortText('Nazwisko')
        );
        $user->activate();
        $manager->persist($user);

        $this->addReference(self::AUTHOR, $user);

        $administrator = new Administrator(Uuid::uuid4(), new Email('admin@example.com'), 'admin');
        $administrator->activate();
        $manager->persist($administrator);

        $manager->flush();
    }
}
