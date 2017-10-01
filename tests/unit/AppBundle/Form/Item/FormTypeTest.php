<?php

namespace Tests\AppBundle\Form\Item;

use Domain\Item\Create;
use Domain\Item\Edit;
use AppBundle\Entity\Item;
use AppBundle\Entity\Scene;
use AppBundle\Form\Item\CreateType;
use AppBundle\Form\Item\EditType;
use Domain\Scene\Create\DTO as SceneDTO;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use UnitTester;

class FormTypeTest extends Unit
{
    const NAME_PL = 'Przedmiot';
    const DESCRIPTION_PL = 'Opis przedmiotu';
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
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getItem()));
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
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getItem()));
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

    private function getItem() : Item
    {
        $createDto = new Create\DTO($this->getScene());
        $createDto->setName(self::NAME_PL);
        return new Item(Uuid::uuid4(), $createDto);
    }

    private function getScene() : Scene
    {
        $createDto = new SceneDTO();
        $createDto->setTitle(self::SCENE_TITLE_PL);
        return new Scene(Uuid::uuid4(), $createDto);
    }
}
