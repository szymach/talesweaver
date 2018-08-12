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
        list($character1Id, $character2Id, $locationId) = $this->getMeetingEntitiesIds($I, $scene);
        $form = $this->fetchCreateForm($I, $scene);
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => $character1Id,
                    'relation' => $character2Id,
                    'location' => $locationId,
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
        $I->assertInstanceOf(Character::class, $model->getRoot());
        $I->assertInstanceOf(Character::class, $model->getRelation());
        $I->assertInstanceOf(Location::class, $model->getLocation());
        $I->assertEquals($character1Id, $model->getRoot()->getId());
        $I->assertEquals($character2Id, $model->getRelation()->getId());
        $I->assertEquals($locationId, $model->getLocation()->getId());
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
        $form = $this->fetchEditForm($I, $scene);
        list($character1Id, $character2Id, $locationId) = $this->getMeetingEntitiesIds($I, $scene);
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => $character1Id,
                    'relation' => $character2Id,
                    'location' => $locationId,
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
        $I->assertInstanceOf(Character::class, $model->getRoot());
        $I->assertInstanceOf(Character::class, $model->getRelation());
        $I->assertInstanceOf(Location::class, $model->getLocation());
        $I->assertEquals($character1Id, $model->getRoot()->getId());
        $I->assertEquals($character2Id, $model->getRelation()->getId());
        $I->assertEquals($locationId, $model->getLocation()->getId());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $I->loginAsUser();
        $scene = $this->getScene($I);
        $form = $this->fetchEditForm($I, $scene);
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

    private function fetchEditForm(FunctionalTester $I, Scene $scene): FormInterface
    {
        return $I->createForm(
            EditType::class,
            new Edit\DTO($this->getEvent($I)),
            ['scene' => $scene, 'model' => MeetingType::class]
        );
    }

    private function getMeetingEntitiesIds(FunctionalTester $I, Scene $scene): array
    {
        $character1 = $this->getCharacter($I, $scene);
        $character2 = $this->getCharacter($I, $scene);
        $location = $this->getLocation($I, $scene);

        $I->persistEntity($scene);
        $I->persistEntity($character1);
        $I->persistEntity($character2);
        $I->persistEntity($location);

        return [
            (string) $character1->getId(),
            (string) $character2->getId(),
            (string) $location->getId()
        ];
    }

    private function getEvent(FunctionalTester $I): Event
    {
        return new Event(
            Uuid::uuid4(),
            new ShortText(self::NAME_PL),
            new Meeting(),
            $this->getScene($I),
            $I->getUser()->getAuthor()
        );
    }
}
