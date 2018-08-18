<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Event;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Event\Create;
use Talesweaver\Application\Event\Edit;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Event\CreateType;
use Talesweaver\Integration\Symfony\Form\Event\EditType;
use Talesweaver\Integration\Symfony\Form\Event\MeetingType;
use Talesweaver\Tests\FunctionalTester;
use Talesweaver\Tests\Integration\Form\CreateCharacterTrait;
use Talesweaver\Tests\Integration\Form\CreateLocationTrait;
use Talesweaver\Tests\Integration\Form\CreateSceneTrait;

class FormTypeCest
{
    use CreateCharacterTrait, CreateLocationTrait, CreateSceneTrait;

    private const NAME_PL = 'Wydarzenie';

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $this->getScene($I);
        list($character1, $character2, $location) = $this->getMeetingEntities($I, $scene);
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => (string) $character1->getId(),
                    'relation' => (string) $character2->getId(),
                    'location' => (string) $location->getId(),
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
        $I->assertEquals(self::NAME_PL, $data->getName());
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
        $scene = $this->getScene($I);
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
        $scene = $this->getScene($I);
        list($character1, $character2, $location) = $this->getMeetingEntities($I, $scene);
        $meeting = new Meeting();
        $meeting->setLocation($location);
        $meeting->setRoot($character1);
        $meeting->setRelation($character2);
        $event = $this->getEvent($I, $meeting);
        $form = $this->fetchEditForm($I, $scene, $event);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => (string) $character1->getId(),
                    'relation' => (string) $character2->getId(),
                    'location' => (string) $location->getId(),
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
        $I->assertEquals(self::NAME_PL, $data->getName());
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
        $scene = $this->getScene($I);
        $event = $this->getEvent($I);
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
            ['scene' => $scene, 'model' => MeetingType::class]
        );
    }

    private function fetchEditForm(FunctionalTester $I, Scene $scene, Event $event): FormInterface
    {
        return $I->createForm(
            EditType::class,
            new Edit\DTO($event),
            ['scene' => $scene, 'model' => MeetingType::class]
        );
    }

    private function getMeetingEntities(FunctionalTester $I, Scene $scene): array
    {
        $character1 = $this->getCharacter($I, $scene);
        $character2 = $this->getCharacter($I, $scene);
        $location = $this->getLocation($I, $scene);

        $I->persistEntity($scene);
        $I->persistEntity($character1);
        $I->persistEntity($character2);
        $I->persistEntity($location);

        return [$character1, $character2, $location];
    }

    private function getEvent(FunctionalTester $I, Meeting $meeting = null): Event
    {
        return new Event(
            Uuid::uuid4(),
            new ShortText(self::NAME_PL),
            $meeting ?? new Meeting(),
            $this->getScene($I),
            $I->getUser()->getAuthor()
        );
    }
}
