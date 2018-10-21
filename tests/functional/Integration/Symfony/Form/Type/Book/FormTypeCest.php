<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeBook;

use Talesweaver\Application\Command\Book\Create;
use Talesweaver\Application\Command\Book\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Book\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Book\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class);
        $form->handleRequest($I->getRequest([
            'create' => ['title' => 'Książka', 'description' => 'Opis']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), 'Książka');
        $I->assertEquals($form->getData()->getDescription(), 'Opis');
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class);
        $form->handleRequest($I->getRequest(['create' => ['title' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $book = $I->haveCreatedABook('Książka');
        $form = $I->createForm(EditType::class, new Edit\DTO($book), ['bookId' => $book->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['title' => 'Książka', 'description' => 'Opis']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), 'Książka');
        $I->assertEquals($form->getData()->getDescription(), 'Opis');
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $book = $I->haveCreatedABook('Książka');
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
