<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeEvent;

use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Command\Event\Create;
use Talesweaver\Application\Command\Event\Edit;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Form\Type\Event\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Event\EditType;
use Talesweaver\Integration\Symfony\Form\Type\Event\MeetingType;
use Talesweaver\Tests\FunctionalTester;

class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $character1 = $I->haveCreatedACharacter('Postać 1', $scene);
        $character2 = $I->haveCreatedACharacter('Postać 2', $scene);
        $location = $I->haveCreatedALocation('Miejsce', $scene);
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => 'Wydarzenie',
                'model' => [
                    'root' => $character1->getId()->toString(),
                    'relation' => $character2->getId()->toString(),
                    'location' => $location->getId()->toString(),
                ]
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
        /* @var $model Meeting */
        $model = $data->getModel();
        $I->assertInstanceOf(Meeting::class, $model);
        $I->assertEquals($character1, $model->getRoot());
        $I->assertEquals($character2, $model->getRelation());
        $I->assertEquals($location, $model->getLocation());
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => null,
                'model' => ['root' => null, 'relation' => null, 'location' => null]
            ],
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(4, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getName(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $character1 = $I->haveCreatedACharacter('Postać 1', $scene);
        $character2 = $I->haveCreatedACharacter('Postać 2', $scene);
        $location = $I->haveCreatedALocation('Miejsce', $scene);
        $event = $I->haveCreatedAnEvent(
            'Spotkanie',
            $scene,
            $I->haveCreatedAMeetingModel($character1, $character2, $location)
        );
        $form = $this->fetchEditForm($I, $scene, $event);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => 'Wydarzenie',
                'model' => [
                    'root' => $character1->getId()->toString(),
                    'relation' => $character2->getId()->toString(),
                    'location' => $location->getId()->toString(),
                ]
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
        /* @var $model Meeting */
        $model = $data->getModel();
        $I->assertInstanceOf(Meeting::class, $model);
        $I->assertEquals($character1, $model->getRoot());
        $I->assertEquals($character2, $model->getRelation());
        $I->assertEquals($location, $model->getLocation());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $character1 = $I->haveCreatedACharacter('Postać 1', $scene);
        $character2 = $I->haveCreatedACharacter('Postać 2', $scene);
        $location = $I->haveCreatedALocation('Miejsce', $scene);
        $event = $I->haveCreatedAnEvent(
            'Spotkanie',
            $scene,
            $I->haveCreatedAMeetingModel($character1, $character2, $location)
        );
        $form = $this->fetchEditForm($I, $scene, $event);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => null,
                'model' => ['root' => null, 'relation' => null, 'location' => null]
            ]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertFalse($form->isValid());
        $I->assertEquals(4, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $data);
        $I->assertEquals(null, $data->getName());
        /* @var $model Meeting */
        $model = $data->getModel();
        $I->assertInstanceOf(Meeting::class, $model);
        $I->assertEquals(null, $model->getRoot());
        $I->assertEquals(null, $model->getRelation());
        $I->assertEquals(null, $model->getLocation());
    }

    private function fetchCreateForm(FunctionalTester $I, Scene $scene): FormInterface
    {
        return $I->createForm(
            CreateType::class,
            new Create\DTO($scene),
            ['scene' => $scene, 'model' => MeetingType::class, 'scene' => $scene]
        );
    }

    private function fetchEditForm(FunctionalTester $I, Scene $scene, Event $event): FormInterface
    {
        return $I->createForm(
            EditType::class,
            new Edit\DTO($event),
            ['scene' => $scene, 'model' => MeetingType::class, 'eventId' => $event->getId()]
        );
    }
}
