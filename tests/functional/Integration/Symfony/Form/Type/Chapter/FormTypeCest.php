<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeChapter;

use Talesweaver\Application\Command\Chapter\Create;
use Talesweaver\Application\Command\Chapter\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, null, ['bookId' => null]);
        $form->handleRequest($I->getRequest(['create' => ['title' => 'Rozdział']]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), 'Rozdział');
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, null, ['bookId' => null]);
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
        $chapter = $I->haveCreatedAChapter('Rozdział');
        $form = $I->createForm(EditType::class, new Edit\DTO($chapter), [
            'bookId' => null,
            'chapterId' => $chapter->getId()
        ]);
        $form->handleRequest($I->getRequest(['edit' => ['title' => 'Rozdział']]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), 'Rozdział');
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $chapter = $I->haveCreatedAChapter('Rozdział');
        $form = $I->createForm(EditType::class, new Edit\DTO($chapter), [
            'bookId' => null,
            'chapterId' => $chapter->getId()
        ]);
        $form->handleRequest($I->getRequest(['edit' => ['title' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }
}
