<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Item;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Item\Create;
use Talesweaver\Application\Item\Edit;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Item\CreateType;
use Talesweaver\Integration\Symfony\Form\Item\EditType;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Integration\Form\CreateSceneTrait;

class FormTypeCest
{
    use CreateSceneTrait;

    private const NAME_PL = 'Przedmiot';
    private const DESCRIPTION_PL = 'Opis przedmiotu';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, new Create\DTO($this->getScene($I)));
        $form->handleRequest($I->getRequest([
            'create' => ['name' => self::NAME_PL]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), self::NAME_PL);
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, new Create\DTO($this->getScene($I)));
        $form->handleRequest($I->getRequest([
            'create' => ['name' => null]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(EditType::class, new Edit\DTO($this->getItem($I)));
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => self::NAME_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), self::NAME_PL);
        $I->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(EditType::class, new Edit\DTO($this->getItem($I)));
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => null]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
        $I->assertEquals($form->getData()->getDescription(), null);
    }

    private function getItem(FunctionalTester $I) : Item
    {
        return new Item(
            Uuid::uuid4(),
            $this->getScene($I),
            new ShortText(self::NAME_PL),
            null,
            null,
            $I->getUser()->getAuthor()
        );
    }
}
