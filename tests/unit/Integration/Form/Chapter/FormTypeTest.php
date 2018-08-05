<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Chapter;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Chapter\Create;
use Talesweaver\Application\Chapter\Edit;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Form\Chapter\EditType;
use UnitTester;

class FormTypeTest extends Unit
{
    private const TITLE_PL = 'Rozdział';

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
        $chapter = new Chapter(Uuid::uuid4(), new ShortText(self::TITLE_PL), null, $this->tester->getUser()->getAuthor());
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
        $chapter = new Chapter(Uuid::uuid4(), new ShortText(self::TITLE_PL), null, $this->tester->getUser()->getAuthor());
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
