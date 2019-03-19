<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeEvent;

use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Command\Event\Create;
use Talesweaver\Application\Command\Event\Edit;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Form\Type\Event\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Event\EditType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => 'Wydarzenie'
            ]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        /* @var $data Create\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Create\DTO::class, $data);
        $I->assertEquals('Wydarzenie', $data->getName());
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => null
            ],
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
        $scene = $I->haveCreatedAScene('Scena');
        $event = $I->haveCreatedAnEvent('Spotkanie', $scene);
        $form = $this->fetchEditForm($I, $scene, $event);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => 'Wydarzenie'
            ]
        ]));
        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertTrue($form->isValid());
        $I->assertEquals(0, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $data);
        $I->assertEquals('Wydarzenie', $data->getName());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $event = $I->haveCreatedAnEvent('Spotkanie', $scene);
        $form = $this->fetchEditForm($I, $scene, $event);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => null
            ]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertFalse($form->isValid());
        $I->assertEquals(1, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $data);
        $I->assertEquals(null, $data->getName());
    }

    private function fetchCreateForm(FunctionalTester $I, Scene $scene): FormInterface
    {
        return $I->createForm(
            CreateType::class,
            new Create\DTO($scene),
            ['scene' => $scene, 'scene' => $scene]
        );
    }

    private function fetchEditForm(FunctionalTester $I, Scene $scene, Event $event): FormInterface
    {
        return $I->createForm(
            EditType::class,
            new Edit\DTO($event),
            ['scene' => $scene, 'eventId' => $event->getId()]
        );
    }
}
