<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Form\Event;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Talesweaver\Application\Event\Create;
use Talesweaver\Application\Event\Edit;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Event\Meeting;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Form\Event\CreateType;
use Talesweaver\Integration\Form\Event\EditType;
use Talesweaver\Integration\Form\Event\MeetingType;
use Talesweaver\Tests\Integration\Form\CreateCharacterTrait;
use Talesweaver\Tests\Integration\Form\CreateLocationTrait;
use Talesweaver\Tests\Integration\Form\CreateSceneTrait;
use UnitTester;

class FormTypeTest extends Unit
{
    use CreateCharacterTrait, CreateLocationTrait, CreateSceneTrait;

    private const NAME_PL = 'Wydarzenie';

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testValidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
        $scene = $this->getScene();
        list($character1Id, $character2Id, $locationId) = $this->getMeetingEntitiesIds($scene);
        $form = $this->fetchCreateForm($scene);
        $form->handleRequest($this->tester->getRequest([
            'create' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => $character1Id,
                    'relation' => $character2Id,
                    'location' => $locationId,
                ]
            ]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(0, count($form->getErrors(true)));
        $this->assertTrue($form->isValid());

        /* @var $data Create\DTO */
        $data = $form->getData();
        $this->assertInstanceOf(Create\DTO::class, $data);
        $this->assertEquals(self::NAME_PL, $data->getName());
        /* @var $model Meeting */
        $model = $data->getModel();
        $this->assertInstanceOf(Meeting::class, $model);
        $this->assertInstanceOf(Character::class, $model->getRoot());
        $this->assertInstanceOf(Character::class, $model->getRelation());
        $this->assertInstanceOf(Location::class, $model->getLocation());
        $this->assertEquals($character1Id, $model->getRoot()->getId());
        $this->assertEquals($character2Id, $model->getRelation()->getId());
        $this->assertEquals($locationId, $model->getLocation()->getId());
    }

    public function testInvalidCreateFormSubmission()
    {
        $this->tester->loginAsUser();
        $scene = $this->getScene();
        $form = $this->fetchCreateForm($scene);
        $form->handleRequest($this->tester->getRequest([
            'create' => [
                'name' => null,
                'model' => ['root' => null, 'relation' => null, 'location' => null]
            ],
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals(4, count($form->getErrors(true)));
        $this->assertFalse($form->isValid());

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), null);
    }

    public function testValidEditFormSubmission()
    {
        $this->tester->loginAsUser();
        $scene = $this->getScene();
        $form = $this->fetchEditForm($scene);
        list($character1Id, $character2Id, $locationId) = $this->getMeetingEntitiesIds($scene);
        $form->handleRequest($this->tester->getRequest([
            'edit' => [
                'name' => self::NAME_PL,
                'model' => [
                    'root' => $character1Id,
                    'relation' => $character2Id,
                    'location' => $locationId,
                ]
            ]
        ]));
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
        $this->assertEquals(0, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $this->assertInstanceOf(Edit\DTO::class, $data);
        $this->assertEquals(self::NAME_PL, $data->getName());
        /* @var $model Meeting */
        $model = $data->getModel();
        $this->assertInstanceOf(Meeting::class, $model);
        $this->assertInstanceOf(Character::class, $model->getRoot());
        $this->assertInstanceOf(Character::class, $model->getRelation());
        $this->assertInstanceOf(Location::class, $model->getLocation());
        $this->assertEquals($character1Id, $model->getRoot()->getId());
        $this->assertEquals($character2Id, $model->getRelation()->getId());
        $this->assertEquals($locationId, $model->getLocation()->getId());
    }

    public function testInvalidEditFormSubmission()
    {
        $this->tester->loginAsUser();
        $scene = $this->getScene();
        $form = $this->fetchEditForm($scene);
        $form->handleRequest($this->tester->getRequest([
            'edit' => [
                'name' => null,
                'model' => ['root' => null, 'relation' => null, 'location' => null]
            ]
        ]));

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertEquals(4, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $this->assertInstanceOf(Edit\DTO::class, $data);
        $this->assertEquals(null, $data->getName());
        /* @var $model Meeting */
        $model = $data->getModel();
        $this->assertInstanceOf(Meeting::class, $model);
        $this->assertEquals(null, $model->getRoot());
        $this->assertEquals(null, $model->getRelation());
        $this->assertEquals(null, $model->getLocation());
    }

    private function fetchCreateForm(Scene $scene): FormInterface
    {
        return $this->tester->createForm(
            CreateType::class,
            new Create\DTO($scene),
            ['scene' => $scene, 'model' => MeetingType::class]
        );
    }

    private function fetchEditForm(Scene $scene): FormInterface
    {
        return $this->tester->createForm(
            EditType::class,
            new Edit\DTO($this->getEvent()),
            ['scene' => $scene, 'model' => MeetingType::class]
        );
    }

    private function getMeetingEntitiesIds(Scene $scene): array
    {
        $character1 = $this->getCharacter($scene);
        $character2 = $this->getCharacter($scene);
        $location = $this->getLocation($scene);

        $this->tester->persistEntity($scene);
        $this->tester->persistEntity($character1);
        $this->tester->persistEntity($character2);
        $this->tester->persistEntity($location);

        return [
            (string) $character1->getId(),
            (string) $character2->getId(),
            (string) $location->getId()
        ];
    }

    private function getEvent(): Event
    {
        return new Event(
            Uuid::uuid4(),
            self::NAME_PL,
            new Meeting(),
            $this->getScene(),
            $this->tester->getUser()->getAuthor()
        );
    }
}
