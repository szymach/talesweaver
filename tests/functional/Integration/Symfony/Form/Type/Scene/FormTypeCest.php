<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeScene;

use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Command\Scene\Create;
use Talesweaver\Application\Command\Scene\Create\DTO;
use Talesweaver\Application\Command\Scene\Edit;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Type\Scene\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Scene\EditType;
use Talesweaver\Tests\FunctionalTester;
use function count;

final class FormTypeCest
{
    /**
     * @var string
     */
    private $chapter1Id;

    /**
     * @var string
     */
    private $chapter2Id;

    public function testValidCreateFormSubmission(FunctionalTester $I)
    {
        $form = $I->createForm(CreateType::class, null);
        $form->handleRequest(
            $I->getRequest(['create' => ['title' => 'Scena', 'chapter' => $this->chapter1Id]])
        );

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        /** @var DTO $dto */
        $dto = $form->getData();
        $I->assertInstanceOf(Create\DTO::class, $dto);
        $I->assertEquals($dto->getTitle(), 'Scena');
        $I->assertEquals($this->chapter1Id, $dto->getChapter()->getId()->toString());
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I)
    {
        $form = $I->createForm(CreateType::class, null);
        $form->handleRequest(
            $I->getRequest(['create' => ['title' => null]])
        );

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I)
    {
        $scene = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $I->getAuthor());
        $form = $I->createForm(EditType::class, new Edit\DTO($scene), ['sceneId' => $scene->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['title' => 'Scena', 'text' => 'Treść sceny', 'chapter' => $this->chapter2Id]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        /** @var DTO $dto */
        $dto = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $dto);
        $I->assertEquals($dto->getTitle(), 'Scena');
        $I->assertEquals($dto->getText(), 'Treść sceny');
        $I->assertEquals($this->chapter2Id, $dto->getChapter()->getId()->toString());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I)
    {
        $scene = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $I->getAuthor());
        $form = $I->createForm(EditType::class, new Edit\DTO($scene), ['sceneId' => $scene->getId()]);
        $form->handleRequest($I->getRequest([
            'edit' => ['title' => null, 'text' => null]
        ]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
        $I->assertEquals($form->getData()->getText(), null);
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Chapter $chapter1 */
        $chapter1 = $I->haveCreatedAChapter('Rozdział 1');
        $this->chapter1Id = $chapter1->getId()->toString();

        /** @var Chapter $chapter2 */
        $chapter2 = $I->haveCreatedAChapter('Rozdział 2');
        $this->chapter2Id = $chapter2->getId()->toString();
    }
}
