<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Scene;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Scene\Create;
use Talesweaver\Application\Scene\Edit;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Form\Scene\CreateType;
use Talesweaver\Integration\Form\Scene\EditType;
use UnitTester;

class FormTypeTest extends Unit
{
    private const TITLE_PL = 'Scena';
    private const TEXT_PL = 'Treść sceny';

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
        $scene = new Scene(Uuid::uuid4(), self::TITLE_PL, null, $this->tester->getUser());
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
        $this->tester->loginAsUser();
        $scene = new Scene(Uuid::uuid4(), self::TITLE_PL, null, $this->tester->getUser());
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
