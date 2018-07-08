<?php

declare(strict_types=1);

namespace Integration\Tests\Form\Chapter;

use Integration\Form\Chapter\CreateType;
use Integration\Form\Chapter\EditType;
use Codeception\Test\Unit;
use Application\Chapter\Create;
use Application\Chapter\Edit;
use Domain\Chapter;
use Ramsey\Uuid\Uuid;
use UnitTester;

class FormTypeTest extends Unit
{
    const TITLE_PL = 'Rozdział';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
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
        $chapter = new Chapter(Uuid::uuid4(), self::TITLE_PL, null, $this->tester->getUser());
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($chapter));
        $form->handleRequest($this->tester->getRequest(['edit' => ['title' => self::TITLE_PL]]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), self::TITLE_PL);
    }

    public function testInvalidEditFormSubmission()
    {
        $this->tester->loginAsUser();
        $chapter = new Chapter(Uuid::uuid4(), self::TITLE_PL, null, $this->tester->getUser());
        $form = $this->tester->createForm(EditType::class, new Edit\DTO($chapter));
        $form->handleRequest($this->tester->getRequest(['edit' => ['title' => null]]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(1, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getTitle(), null);
    }
}
