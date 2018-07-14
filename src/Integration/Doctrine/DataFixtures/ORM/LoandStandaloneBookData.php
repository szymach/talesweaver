<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Doctrine\Entity\User;

class LoandStandaloneBookData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        /* @var $user User */
        $user = $manager->getRepository(User::class)->findOneBy([]);
        $author = $user->getAuthor();

        $book = new Book(Uuid::uuid4(), 'Książka', $author);
        $book->setLocale(self::LOCALE);

        $chapter = new Chapter(Uuid::uuid4(), 'Rozdział 1', $book, $author);
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
