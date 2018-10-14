<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Chapter\Create\Command;
use Talesweaver\Application\Query\Chapter\ById;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class ChapterModule extends Module
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

    public function haveCreatedAChapter(string $title, Book $book = null): UuidInterface
    {
        $id = Uuid::uuid4();
        $this->commandBus->dispatch(new Command($id, new ShortText($title), $book));

        $this->assertInstanceOf(Chapter::class, $this->queryBus->query(new ById($id)));

        return $id;
    }

    public function seeChapterHasBeenRemoved(UuidInterface $id): void
    {
        $this->assertNull($this->queryBus->query(new ById($id)));
    }
}
