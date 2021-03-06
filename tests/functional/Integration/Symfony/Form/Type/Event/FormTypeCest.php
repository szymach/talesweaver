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

final class FormTypeCest
{
    public function testValidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $character1 = $I->haveCreatedACharacter('Postać 1', $scene);
        $character2 = $I->haveCreatedACharacter('Postać 2', $scene);
        $item1 = $I->haveCreatedAnItem('Przedmiot 1', $scene);
        $item2 = $I->haveCreatedAnItem('Przedmiot 2', $scene);
        $location1 = $I->haveCreatedALocation('Miejsce 1', $scene);
        $location2 = $I->haveCreatedALocation('Miejsce 2', $scene);
        $form = $this->fetchCreateForm(
            $I,
            $scene,
            [$character1, $character2],
            [$item1, $item2],
            [$location1, $location2]
        );
        $form->handleRequest($I->getRequest([
            'create' => [
                'name' => 'Wydarzenie',
                'characters' => [
                    0 => $character2->getId()->toString()
                ],
                'items' => [
                    0 => $item1->getId()->toString()
                ],
                'location' => $location2->getId()->toString()
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
        $I->assertCount(1, $data->getCharacters());
        $I->assertEquals('Postać 2', (string) $data->getCharacters()[0]->getName());
        $I->assertEquals('Przedmiot 1', (string) $data->getItems()[0]->getName());
        $I->assertEquals('Miejsce 2', (string) $data->getLocation()->getName());
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $form = $this->fetchCreateForm($I, $scene, [], [], []);
        $form->handleRequest($I->getRequest(['create' => ['name' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        /* @var $data Create\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Create\DTO::class, $data);
        $I->assertEquals(null, $data->getName());
        $I->assertEquals([], $data->getCharacters());
        $I->assertEquals([], $data->getItems());
    }

    public function testValidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $event = $I->haveCreatedAnEvent('Spotkanie', $scene);
        $character1 = $I->haveCreatedACharacter('Postać 1', $scene);
        $character2 = $I->haveCreatedACharacter('Postać 2', $scene);
        $item1 = $I->haveCreatedAnItem('Przedmiot 1', $scene);
        $item2 = $I->haveCreatedAnItem('Przedmiot 2', $scene);
        $location1 = $I->haveCreatedALocation('Miejsce 1', $scene);
        $location2 = $I->haveCreatedALocation('Miejsce 2', $scene);
        $form = $this->fetchEditForm(
            $I,
            $scene,
            $event,
            [$character1, $character2],
            [$item1, $item2],
            [$location1, $location2]
        );
        $form->handleRequest($I->getRequest([
            'edit' => [
                'name' => 'Wydarzenie',
                'characters' => [
                    0 => $character2->getId()->toString()
                ],
                'items' => [
                    0 => $item2->getId()->toString()
                ],
                'location' => $location1->getId()->toString()
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
        $I->assertEquals('Postać 2', (string) $data->getCharacters()[0]->getName());
        $I->assertEquals('Przedmiot 2', (string) $data->getItems()[0]->getName());
        $I->assertEquals('Miejsce 1', (string) $data->getLocation()->getName());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $scene = $I->haveCreatedAScene('Scena');
        $event = $I->haveCreatedAnEvent('Spotkanie', $scene);
        $form = $this->fetchEditForm($I, $scene, $event, [], [], []);
        $form->handleRequest($I->getRequest(['edit' => ['name' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertFalse($form->isValid());
        $I->assertEquals(1, count($form->getErrors(true)));

        /* @var $data Edit\DTO */
        $data = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $data);
        $I->assertEquals(null, $data->getName());
        $I->assertEquals([], $data->getCharacters());
    }

    private function fetchCreateForm(
        FunctionalTester $I,
        Scene $scene,
        array $characters,
        array $items,
        array $locations
    ): FormInterface {
        return $I->createForm(
            CreateType::class,
            new Create\DTO($scene),
            ['characters' => $characters, 'items' => $items, 'scene' => $scene, 'locations' => $locations]
        );
    }

    private function fetchEditForm(
        FunctionalTester $I,
        Scene $scene,
        Event $event,
        array $characters,
        array $items,
        array $locations
    ): FormInterface {
        return $I->createForm(
            EditType::class,
            new Edit\DTO($event),
            [
                'eventId' => $event->getId(),
                'characters' => $characters,
                'items' => $items,
                'scene' => $scene,
                'locations' => $locations
            ]
        );
    }
}
