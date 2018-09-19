<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeLocation;

use Talesweaver\Application\Command\Location\Create;
use Talesweaver\Application\Command\Location\Edit;
use Talesweaver\Integration\Symfony\Form\Type\Location\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Location\EditType;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Integration\Symfony\Form\CreateLocationTrait;
use Talesweaver\Tests\Integration\Symfony\Form\CreateSceneTrait;

class FormTypeCest
{
    use CreateLocationTrait, CreateSceneTrait;

    private const NAME_PL = 'Miejsce';
    private const DESCRIPTION_PL = 'Opis miejsca';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $this->getScene($I);
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['sceneId' => $scene->getId()]);
        $form->handleRequest($I->getRequest(['create' => ['name' => self::NAME_PL]]));

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
        $scene = $this->getScene($I);
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['sceneId' => $scene->getId()]);
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
        $location = $this->getLocation($I);
        $form = $I->createForm(EditType::class, new Edit\DTO($location), ['locationId' => $location->getId()]);
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
        $location = $this->getLocation($I);
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
