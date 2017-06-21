<?php

namespace Tests\AppBundle\Form\Book;

use AppBundle\Book\Create;
use AppBundle\Book\Edit;
use AppBundle\Entity\Book;
use AppBundle\Form\Book\CreateType;
use AppBundle\Form\Book\EditType;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use UnitTester;

class FormTypeTest extends Unit
{
    const TITLE_PL = 'Książka';
    const DESCRIPTION_PL = 'Opis';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $form = $this->tester->createForm(CreateType::class);
        $form->handleRequest($this->tester->getRequest([
            'create' => ['title' => self::TITLE_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
        $this->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
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
        $book = new Book(Uuid::uuid4(), self::TITLE_PL);
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($book));
        $form->handleRequest($this->tester->getRequest([
            'edit' => ['title' => self::TITLE_PL, 'description' => self::DESCRIPTION_PL]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
        $this->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
    }

    public function testInvalidEditFormSubmission()
    {
        $book = new Book(Uuid::uuid4(), self::TITLE_PL);
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($book));
        $form->handleRequest($this->tester->getRequest(['edit' => ['title' => null]]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), null);
    }
}
