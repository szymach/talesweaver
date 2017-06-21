<?php

namespace Tests\AppBundle\Form\Scene;

use AppBundle\Scene\Create;
use AppBundle\Scene\Edit;
use AppBundle\Entity\Scene;
use AppBundle\Form\Scene\CreateType;
use AppBundle\Form\Scene\EditType;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use UnitTester;

class FormTypeTest extends Unit
{
    const TITLE_PL = 'Scena';
    const TEXT_PL = 'Treść sceny';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $form = $this->tester->createForm(CreateType::class);
        $form->handleRequest($this->tester->getRequest([
            'create' => ['title' => self::TITLE_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
    }

    public function testInvalidCreateFormSubmission()
    {
        $form = $this->tester->createForm(CreateType::class);
        $form->handleRequest($this->tester->getRequest([
            'create' => ['title' => null]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), null);
    }

    public function testValidEditFormSubmission()
    {
        $createDto = new Create\DTO();
        $createDto->setTitle(self::TITLE_PL);
        $scene = new Scene(Uuid::uuid4(), $createDto);
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($scene));
        $form->handleRequest($this->tester->getRequest([
            'edit' => ['title' => self::TITLE_PL, 'text' => self::TEXT_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
        $this->assertEquals($form->getData()->getText(), self::TEXT_PL);
    }

    public function testInvalidEditFormSubmission()
    {
        $createDto = new Create\DTO();
        $createDto->setTitle(self::TITLE_PL);
        $scene = new Scene(Uuid::uuid4(), $createDto);
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($scene));
        $form->handleRequest($this->tester->getRequest([
            'edit' => ['title' => null, 'text' => null]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), null);
        $this->assertEquals($form->getData()->getText(), null);
    }
}
