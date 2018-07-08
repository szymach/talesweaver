<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Book;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Book\Create;
use Talesweaver\Application\Book\Edit;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Form\Book\CreateType;
use Talesweaver\Integration\Form\Book\EditType;
use UnitTester;

class FormTypeTest extends Unit
{
    private const TITLE_PL = 'Książka';
    private const DESCRIPTION_PL = 'Opis';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
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
        $this->tester->loginAsUser();
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
        $this->tester->loginAsUser();
        $book = new Book(Uuid::uuid4(), self::TITLE_PL, $this->tester->getUser());
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
        $this->tester->loginAsUser();
        $book = new Book(Uuid::uuid4(), self::TITLE_PL, $this->tester->getUser());
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
