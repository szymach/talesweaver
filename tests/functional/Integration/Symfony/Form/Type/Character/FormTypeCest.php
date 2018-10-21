<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeCharacter;

use Talesweaver\Application\Command\Character\Create;
use Talesweaver\Application\Command\Character\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Character\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Character\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['sceneId' => $scene->getId()]);
        $form->handleRequest($I->getRequest(['create' => ['name' => 'Postać']]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertTrue($form->isValid());
        $I->assertEquals(0, count($form->getErrors(true)));

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Postać');
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['sceneId' => $scene->getId()]);
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
        $character = $I->haveCreatedACharacter('Postać', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($character), ['characterId' => $character->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => 'Postać', 'description' => 'Opis postaci']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertTrue($form->isValid());
        $I->assertEquals(0, count($form->getErrors(true)));

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Postać');
        $I->assertEquals($form->getData()->getDescription(), 'Opis postaci');
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $character = $I->haveCreatedACharacter('Postać', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($character), ['characterId' => $character->getId()]);
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
