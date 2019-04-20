<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Timeline;

use DateTimeImmutable;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;

class FullHandler implements QueryHandlerInterface
{
    private const ICONS = [
        Book::class => 'book',
        Chapter::class => 'clipboard',
        Scene::class => 'sticky-note'
    ];

    private const ROUTES = [
        Book::class => 'book_edit',
        Chapter::class => 'chapter_edit',
        Scene::class => 'scene_edit'
    ];

    /**
     * @var Books
     */
    private $bookRepository;

    /**
     * @var Chapters
     */
    private $chapterRepository;

    /**
     * @var Scenes
     */
    private $sceneRepository;

    public function __construct(
        Books $bookRepository,
        Chapters $chapterRepository,
        Scenes $sceneRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
    }

    public function __invoke(Full $query): array
    {
        $timeline = [];

        $this->addItems($timeline, $this->bookRepository, Book::class);
        $this->addItems($timeline, $this->chapterRepository, Chapter::class);
        $this->addItems($timeline, $this->sceneRepository, Scene::class);

        uasort($timeline, function (array $a, array $b): int {
            return new DateTimeImmutable($b['date']) <=> new DateTimeImmutable($a['date']);
        });

        return $timeline;
    }

    private function addItems(
        array &$timeline,
        LatestChangesAwareRepository $repository,
        string $class
    ): void {
        $callback = function (array $timeline, array $item) use ($class): array {
            $timeline[] = array_merge(
                $item,
                ['icon' => self::ICONS[$class], 'route' => self::ROUTES[$class], 'class' => $class]
            );

            return $timeline;
        };

        $timeline = array_reduce($repository->findLatest(), $callback, $timeline);
    }
}
