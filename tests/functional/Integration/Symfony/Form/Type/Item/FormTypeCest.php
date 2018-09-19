<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeItem;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Command\Item\Create;
use Talesweaver\Application\Command\Item\Edit;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Type\Item\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Item\EditType;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Integration\Symfony\Form\CreateSceneTrait;

class FormTypeCest
{
    use CreateSceneTrait;

    private const NAME_PL = 'Przedmiot';
    private const DESCRIPTION_PL = 'Opis przedmiotu';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $this->getScene($I);
        $form = $I->createForm(CreateType::class, new Create\DTO($scene), ['sceneId' => $scene->getId()]);
        $form->handleRequest($I->getRequest([
            'create' => ['name' => self::NAME_PL]
        ]));

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
        $form->handleRequest($I->getRequest([
            'create' => ['name' => null]
        ]));

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
        $item = $this->getItem($I);
        $form = $I->createForm(EditType::class, new Edit\DTO($item), ['itemId' => $item->getId()]);
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
        $item = $this->getItem($I);
        $form = $I->createForm(EditType::class, new Edit\DTO($item), ['itemId' => $item->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['name' => null]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
        $I->assertEquals($form->getData()->getDescription(), null);
    }

    private function getItem(FunctionalTester $I) : Item
    {
        return new Item(
            Uuid::uuid4(),
            $this->getScene($I),
            new ShortText(self::NAME_PL),
            null,
            null,
            $I->getAuthor()
        );
    }
}
