<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Book\Create\Command;
use Talesweaver\Application\Query\Book\ById;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\Query\Book\ByTitle;

class BookModule extends Module
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function _before(TestInterface $test)
    {
        /* @var $container ContainerModule */
        $container = $this->getModule(ContainerModule::class);
        $this->commandBus = $container->getService(CommandBus::class);
        $this->queryBus = $container->getService(QueryBus::class);
    }

    public function haveCreatedABook(string $title): UuidInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($id, new ShortText($title)));

        $this->assertInstanceOf(Book::class, $this->queryBus->query(new ById($id)));

        return $id;
    }

    public function grabBookByTitle(string $title): Book
    {
        $book = $this->queryBus->query(new ByTitle(new ShortText($title)));
        $this->assertNotNull($book);

        return $book;
    }

    public function seeBookHasBeenCreated(string $title): void
    {
        $this->assertNotNull($this->grabBookByTitle($title));
    }

    public function seeBookHasBeenRemoved(UuidInterface $id): void
    {
        $this->assertNull($this->queryBus->query(new ById($id)));
    }
}
