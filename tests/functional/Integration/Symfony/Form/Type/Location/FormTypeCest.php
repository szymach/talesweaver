<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeLocation;

use Talesweaver\Application\Command\Location\Create;
use Talesweaver\Application\Command\Location\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Location\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Location\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['scene' => $scene]);
        $form->handleRequest($I->getRequest(['create' => ['name' => 'Miejsce']]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Miejsce');
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['scene' => $scene]);
        $form->handleRequest($I->getRequest(['create' => ['name' => null]]));

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
        $location = $I->haveCreatedALocation('Miejsce', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($location), ['locationId' => $location->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => 'Miejsce', 'description' => 'Opis miejsca']
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), 'Miejsce');
        $I->assertEquals($form->getData()->getDescription(), 'Opis miejsca');
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $location = $I->haveCreatedALocation('Miejsce', $I->haveCreatedAScene('Scena'));
        $form = $I->createForm(EditType::class, new Edit\DTO($location), ['locationId' => $location->getId()]);
        $form->handleRequest($I->getRequest(['edit' => ['name' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
        $I->assertEquals($form->getData()->getDescription(), null);
    }
}
