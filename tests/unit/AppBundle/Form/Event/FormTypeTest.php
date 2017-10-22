<?php

namespace Tests\AppBundle\Form\Event;

use AppBundle\Entity\Event;
use AppBundle\Entity\Scene;
use AppBundle\Form\Event\CreateType;
use AppBundle\Form\Event\EditType;
use AppBundle\Form\Event\MeetingType;
use Codeception\Test\Unit;
use Domain\Event\Create;
use Domain\Event\Edit;
use Domain\Event\Meeting;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Tests\AppBundle\Form\CreateCharacterTrait;
use Tests\AppBundle\Form\CreateLocationTrait;
use Tests\AppBundle\Form\CreateSceneTrait;
use UnitTester;

class FormTypeTest extends Unit
{
    use CreateCharacterTrait, CreateLocationTrait, CreateSceneTrait;

    const NAME_PL = 'Wydarzenie';
    const DESCRIPTION_PL = 'Opis postaci';

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

        $this->assertInstanceOf(Create\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), self::NAME_PL);
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

        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
        $this->assertEquals($form->getData()->getName(), self::NAME_PL);
        $this->assertInstanceOf(Meeting::class, $form->getData()->getModel());
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

    private function getEvent() : Event
    {
        $createDto = new Create\DTO($this->getScene());
        $createDto->setName(self::NAME_PL);
        return new Event(Uuid::uuid4(), $createDto, $this->tester->getUser());
    }
}
