<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Character;

use Talesweaver\Application\Character\Create;
use Talesweaver\Application\Character\Edit;
use Talesweaver\Integration\Symfony\Form\Character\CreateType;
use Talesweaver\Integration\Symfony\Form\Character\EditType;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Integration\Form\CreateCharacterTrait;
use Talesweaver\Tests\Integration\Form\CreateSceneTrait;

class FormTypeCest
{
    use CreateCharacterTrait, CreateSceneTrait;

    private const NAME_PL = 'Postać';
    private const DESCRIPTION_PL = 'Opis postaci';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, new Create\DTO($this->getScene($I)));
        $form->handleRequest($I->getRequest(['create' => ['name' => self::NAME_PL]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertTrue($form->isValid());
        $I->assertEquals(0, count($form->getErrors(true)));

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), self::NAME_PL);
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, new Create\DTO($this->getScene($I)));
        $form->handleRequest($I->getRequest(['create' => ['name' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertFalse($form->isValid());
        $I->assertEquals(1, count($form->getErrors(true)));

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(EditType::class, new Edit\DTO($this->getCharacter($I)));
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => self::NAME_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertTrue($form->isValid());
        $I->assertEquals(0, count($form->getErrors(true)));

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), self::NAME_PL);
        $I->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $form = $I->createForm(EditType::class, new Edit\DTO($this->getCharacter($I)));
        $form->handleRequest($I->getRequest(['edit' => ['name' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertFalse($form->isValid());
        $I->assertEquals(1, count($form->getErrors(true)));

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
        $I->assertEquals($form->getData()->getDescription(), null);
    }
}
