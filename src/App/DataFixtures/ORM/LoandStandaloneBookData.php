<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Book;
use Domain\Chapter;
use Domain\User;
use Ramsey\Uuid\Uuid;

class LoandStandaloneBookData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy([]);

        $book = new Book(Uuid::uuid4(), 'Książka', $user);
        $book->setLocale(self::LOCALE);

        $chapter = new Chapter(Uuid::uuid4(), 'Rozdział 1', $book, $user);
        $chapter->setLocale(self::LOCALE);

        $manager->persist($book);
        $manager->persist($chapter);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
