<?php

namespace Tests\AppBundle\Form\Character;


use AppBundle\Form\Character\CreateType;
use AppBundle\Form\Character\EditType;
use Codeception\Test\Unit;
use Domain\Character\Create;
use Domain\Character\Edit;
use Tests\AppBundle\Form\CreateCharacterTrait;
use Tests\AppBundle\Form\CreateSceneTrait;
use UnitTester;

class FormTypeTest extends Unit
{
    use CreateCharacterTrait, CreateSceneTrait;

    const NAME_PL = 'Postać';
    const DESCRIPTION_PL = 'Opis postaci';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
        $form = $this->tester->createForm(CreateType::class, new Create\DTO($this->getScene()));
        $form->handleRequest($this->tester->getRequest([
            'create' => ['name' => self::NAME_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), self::NAME_PL);
    }

    public function testInvalidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
        $form = $this->tester->createForm(CreateType::class, new Create\DTO($this->getScene()));
        $form->handleRequest($this->tester->getRequest([
            'create' => ['name' => null]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), null);
    }

    public function testValidEditFormSubmission()
    {
        $this->tester->loginAsUser();
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getCharacter()));
        $form->handleRequest($this->tester->getRequest([
            'edit' => ['name' => self::NAME_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), self::NAME_PL);
        $this->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidEditFormSubmission()
    {
        $this->tester->loginAsUser();
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getCharacter()));
        $form->handleRequest($this->tester->getRequest([
            'edit' => ['name' => null]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), null);
        $this->assertEquals($form->getData()->getDescription(), null);
    }
}
