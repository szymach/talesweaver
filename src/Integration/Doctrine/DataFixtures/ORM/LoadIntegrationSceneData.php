<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Assert\Assertion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class LoadIntegrationSceneData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const LOCALE = 'pl';

    public static function getGroups(): array
    {
        return ['integration'];
    }

    public function getDependencies(): array
    {
        return [LoadIntegrationBookData::class, LoadIntegrationChapterData::class, LoadUserData::class];
    }

    public function load(ObjectManager $manager)
    {
        /* @var $author Author|null */
        $author = $this->getReference(LoadUserData::AUTHOR);
        Assertion::isInstanceOf($author, Author::class);

        $scene = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $author);
        $scene->setLocale(self::LOCALE);

        $character1 = new Character(Uuid::uuid4(), $scene, new ShortText('Postać do spotkania 1'), null, null, $author);
        $character2 = new Character(Uuid::uuid4(), $scene, new ShortText('Postać do spotkania 2'), null, null, $author);
        $character3 = new Character(Uuid::uuid4(), $scene, new ShortText('Postać do spotkania 3'), null, null, $author);
        $character1->setLocale(self::LOCALE);
        $character2->setLocale(self::LOCALE);
        $character3->setLocale(self::LOCALE);

        $location1 = new Location(Uuid::uuid4(), $scene, new ShortText('Miejsce do spotkania 1'), null, null, $author);
        $location2 = new Location(Uuid::uuid4(), $scene, new ShortText('Miejsce do spotkania 2'), null, null, $author);
        $location1->setLocale(self::LOCALE);
        $location2->setLocale(self::LOCALE);

        $manager->persist($scene);
        $manager->flush();
    }
}
