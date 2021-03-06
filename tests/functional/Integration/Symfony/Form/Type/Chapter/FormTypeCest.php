<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\Symfony\Form\TypeChapter;

use Talesweaver\Application\Command\Chapter\Create;
use Talesweaver\Application\Command\Chapter\Edit;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\CreateType;
use Talesweaver\Integration\Symfony\Form\Type\Chapter\EditType;
use Talesweaver\Tests\FunctionalTester;

final class FormTypeCest
{
    /**
     * @var Book
     */
    private $book1;

    /**
     * @var string
     */
    private $book1Id;

    /**
     * @var string
     */
    private $book2Id;

    public function testValidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, null, ['bookId' => null]);
        $form->handleRequest(
            $I->getRequest(
                [
                    'create' => [
                        'title' => 'Rozdział',
                        'preface' => '<p>Wstęp rozdziału</p>',
                        'book' => $this->book1Id
                    ]
                ]
            )
        );

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        /** @var Create\DTO $dto */
        $dto = $form->getData();
        $I->assertInstanceOf(Create\DTO::class, $dto);
        $I->assertEquals('Rozdział', $dto->getTitle());
        $I->assertEquals('<p>Wstęp rozdziału</p>', $dto->getPreface());
        $I->assertEquals($this->book1Id, $dto->getBook()->getId());
    }

    public function testInvalidCreateFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $form = $I->createForm(CreateType::class, null, ['bookId' => null]);
        $form->handleRequest($I->getRequest(['create' => ['title' => null]]));

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        $I->assertInstanceOf(Create\DTO::class, $form->getData());
        $I->assertEquals($form->getData()->getTitle(), null);
    }

    public function testValidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $chapter = $I->haveCreatedAChapter('Rozdział', '<p>Wstęp rozdziału</p>', $this->book1);
        $form = $I->createForm(EditType::class, new Edit\DTO($chapter), [
            'bookId' => null,
            'chapterId' => $chapter->getId()
        ]);
        $form->handleRequest(
            $I->getRequest(
                [
                    'edit' => [
                        'title' => 'Rozdział',
                        'preface' => '<p>Zmieniony wstęp rozdziału</p>',
                    ]
                ]
            )
        );

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(0, count($form->getErrors(true)));
        $I->assertTrue($form->isValid());

        /** @var Edit\DTO $dto */
        $dto = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertEquals('Rozdział', $dto->getTitle());
        $I->assertEquals('<p>Zmieniony wstęp rozdziału</p>', $dto->getPreface());
        $I->assertNull($dto->getBook());
    }

    public function testInvalidEditFormSubmission(FunctionalTester $I): void
    {
        $I->loginAsUser();
        $chapter = $I->haveCreatedAChapter('Rozdział');
        $form = $I->createForm(EditType::class, new Edit\DTO($chapter), [
            'bookId' => null,
            'chapterId' => $chapter->getId()
        ]);
        $form->handleRequest(
            $I->getRequest(['edit' => ['title' => null]])
        );

        $I->assertTrue($form->isSynchronized());
        $I->assertTrue($form->isSubmitted());
        $I->assertEquals(1, count($form->getErrors(true)));
        $I->assertFalse($form->isValid());

        /** @var Edit\DTO $dto */
        $dto = $form->getData();
        $I->assertInstanceOf(Edit\DTO::class, $form->getData());
        $I->assertNull($dto->getTitle());
        $I->assertNull($dto->getPreface());
        $I->assertNull($dto->getBook());
    }

    /**
     * @phpcs:disable
     */
    public function _before(FunctionalTester $I): void
    {
        $I->loginAsUser();

        /** @var Book $book1 */
        $book1 = $I->haveCreatedABook('Książka 1');
        $this->book1 = $book1;
        $this->book1Id = $book1->getId()->toString();

        /** @var Book $book2 */
        $book2 = $I->haveCreatedABook('Książka 2');
        $this->book2Id = $book2->getId()->toString();
    }
}
