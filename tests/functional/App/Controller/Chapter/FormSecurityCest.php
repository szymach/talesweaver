<?php

declare(strict_types=1);

namespace App\Tests\Controller\Chapter;

use App\Tests\FunctionalTester;
use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Ramsey\Uuid\Uuid;

class FormSecurityCest
{
    private const CREATE_URL = '/pl/chapter/create';
    private const CREATE_FORM = 'form[name="create"]';

    private const EDIT_URL = '/pl/chapter/edit/%s';
    private const EDIT_FORM = 'form[name="edit"]';

    private const BOOK_TITLE = 'Książka';
    private const CHAPTER_TITLE = 'Rozdział';
    private const OTHER_USER_USERNAME = 'other_user@example.com';

    public function createWithOtherUsersBook(FunctionalTester $I)
    {
        $I->loginAsUser();

        $otherUser = $I->getUser(true, self::OTHER_USER_USERNAME);
        $otherUsersBookId = Uuid::uuid4();

        $I->persistEntity(new Book(Uuid::uuid4(), self::BOOK_TITLE, $I->getUser()));
        $I->persistEntity(
            new Book($otherUsersBookId, self::BOOK_TITLE, $otherUser)
        );
        $I->flushToDatabase();

        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, [
            'create[title]' => self::CHAPTER_TITLE,
            'create[book]' => $otherUsersBookId->toString()
        ]);
        $I->seeCurrentUrlEquals(self::CREATE_URL);
        $I->seeError('Ta wartość jest nieprawidłowa.', 'create[book]');
    }

    public function editWithOtherUsersBook(FunctionalTester $I)
    {
        $I->loginAsUser();

        $otherUser = $I->getUser(true, self::OTHER_USER_USERNAME);
        $otherUsersBookId = Uuid::uuid4();

        $book = new Book(Uuid::uuid4(), self::BOOK_TITLE, $I->getUser());
        $I->persistEntity($book);
        $I->persistEntity(
            new Book($otherUsersBookId, self::BOOK_TITLE, $otherUser)
        );
        $chapterId = Uuid::uuid4();
        $I->persistEntity(new Chapter($chapterId, self::CHAPTER_TITLE, $book, $I->getUser()));
        $I->flushToDatabase();

        $I->amOnPage(self::CREATE_URL);
        $I->submitForm(self::CREATE_FORM, [
            'create[title]' => self::CHAPTER_TITLE,
            'create[book]' => $otherUsersBookId->toString()
        ]);
        $I->seeCurrentUrlEquals(self::CREATE_URL);
        $I->seeError('Ta wartość jest nieprawidłowa.', 'create[book]');
    }
}
