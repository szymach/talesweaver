<?php

namespace Tests\AppBundle\Form\Event;

use AppBundle\Entity\Event;
use AppBundle\Entity\Scene;
use AppBundle\Form\Event\CreateType;
use AppBundle\Form\Event\MeetingType;
use Codeception\Test\Unit;
use Domain\Event\Create;
use Domain\Event\Meeting;
use Ramsey\Uuid\Uuid;
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

//    public function testValidEditFormSubmission()
//    {
//        $this->tester->loginAsUser();
//        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getEvent()));
//        $form->handleRequest($this->tester->getRequest([
//            'edit' => ['name' => self::NAME_PL, 'description' => self::DESCRIPTION_PL]
//        ]));
//
//        $this->assertTrue($form->isSynchronized());
//        $this->assertTrue($form->isSubmitted());
//        $this->assertEquals(0, count($form->getErrors(true)));
//        $this->assertTrue($form->isValid());
//
//        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
//        $this->assertEquals($form->getData()->getName(), self::NAME_PL);
//        $this->assertEquals($form->getData()->getDescription(), self::DESCRIPTION_PL);
//    }
//
//    public function testInvalidEditFormSubmission()
//    {
//        $this->tester->loginAsUser();
//        $form = $this->tester->createForm(EditType::class, new Edit\DTO($this->getEvent()));
//        $form->handleRequest($this->tester->getRequest([
//            'edit' => ['name' => null]
//        ]));
//
//        $this->assertTrue($form->isSynchronized());
//        $this->assertTrue($form->isSubmitted());
//        $this->assertEquals(1, count($form->getErrors(true)));
//        $this->assertFalse($form->isValid());
//
//        $this->assertInstanceOf(Edit\DTO::class, $form->getData());
//        $this->assertEquals($form->getData()->getName(), null);
//        $this->assertEquals($form->getData()->getDescription(), null);
//    }

    private function fetchCreateForm(Scene $scene)
    {
        return $this->tester->createForm(
            CreateType::class,
            new Create\DTO($scene),
            [
                'scene' => $scene,
                'model' => MeetingType::class,
                'action' => $this->tester->getRouter()->generate(
                    'app_event_add',
                    ['id' => $scene->getId(), 'model' => Meeting::class]
                )
            ]
        );
    }

    private function getMeetingEntitiesIds(Scene $scene): array
    {
        $character1 = $this->getCharacter($scene);
        $character2 = $this->getCharacter($scene);
        $location = $this->getLocation($scene);

        $manager = $this->tester->getEntityManager();
        $manager->persist($scene);
        $manager->persist($character1);
        $manager->persist($character2);
        $manager->persist($location);

        $manager->flush();

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
