<?php

namespace Tests\AppBundle\Form\Character;

use Domain\Character\Create;
use Domain\Character\Edit;
use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Form\Character\CreateType;
use AppBundle\Form\Character\EditType;
use Domain\Scene\Create\DTO as SceneDTO;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use UnitTester;

class FormTypeTest extends Unit
{
    const NAME_PL = 'Postać';
    const DESCRIPTION_PL = 'Opis postaci';
    const SCENE_TITLE_PL = 'Scena';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
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

    private function getCharacter() : Character
    {
        $createDto = new Create\DTO($this->getScene());
        $createDto->setName(self::NAME_PL);
        return new Character(Uuid::uuid4(), $createDto);
    }

    private function getScene() : Scene
    {
        $createDto = new SceneDTO();
        $createDto->setTitle(self::SCENE_TITLE_PL);
        return new Scene(Uuid::uuid4(), $createDto);
    }
}
