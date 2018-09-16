<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\TypeChapter;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Chapter\Create;
use Talesweaver\Application\Chapter\Edit;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    private const TITLE_PL = 'Rozdział';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, null, ['bookId' => null]);
        $form->handleRequest($I->getRequest(['create' => ['title' => self::TITLE_PL]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
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

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $chapter = new Chapter(
            Uuid::uuid4(),
            new ShortText(self::TITLE_PL),
            null,
            $I->getAuthor()
        );
        $form = $I->createForm(EditType::class, new Edit\DTO($chapter), [
            'bookId' => null,
            'chapterId' => $chapter->getId()
        ]);
        $form->handleRequest($I->getRequest(['edit' => ['title' => self::TITLE_PL]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $chapter = new Chapter(
            Uuid::uuid4(),
            new ShortText(self::TITLE_PL),
            null,
            $I->getAuthor()
        );
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
