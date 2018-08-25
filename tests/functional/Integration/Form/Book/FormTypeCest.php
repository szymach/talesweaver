<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Book;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Book\Create;
use Talesweaver\Application\Book\Edit;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Book\CreateType;
use Talesweaver\Integration\Symfony\Form\Book\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    private const TITLE_PL = 'Książka';
    private const DESCRIPTION_PL = 'Opis';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class);
        $form->handleRequest($I->getRequest([
            'create' => ['title' => self::TITLE_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
        $I->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class);
        $form->handleRequest($I->getRequest([
            'create' => ['title' => null]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $book = new Book(Uuid::uuid4(), new ShortText(self::TITLE_PL), $I->getUser()->getAuthor());
        $form = $I->createForm(EditType::class, new Edit\DTO($book), ['bookId' => $book->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['title' => self::TITLE_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
        $I->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $book = new Book(Uuid::uuid4(), new ShortText(self::TITLE_PL), $I->getUser()->getAuthor());
        $form = $I->createForm(EditType::class, new Edit\DTO($book), ['bookId' => $book->getId()]);
        $form->handleRequest($I->getRequest(['edit' => ['title' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }
}
