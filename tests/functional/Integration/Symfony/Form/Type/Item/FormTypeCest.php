<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeItem;

use Talesweaver\Application\Command\Item\Create;
use Talesweaver\Application\Command\Item\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Item\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Item\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['scene' => $scene]);
        $form->handleRequest($I->getRequest([
            'create' => ['name' => 'Przedmiot']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Przedmiot');
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['scene' => $scene]);
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
        $item = $I->haveCreatedAnItem('Przedmiot', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($item), ['itemId' => $item->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => 'Przedmiot', 'description' => 'Opis przedmiotu']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Przedmiot');
        $I->assertEquals($form->getData()->getDescription(), 'Opis przedmiotu');
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $item = $I->haveCreatedAnItem('Przedmiot', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($item), ['itemId' => $item->getId()]);
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
}
