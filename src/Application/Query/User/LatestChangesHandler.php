<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\User;

use DateTimeImmutable;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;

final class LatestChangesHandler implements QueryHandlerInterface
{
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

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        Books $bookRepository,
        Chapters $chapterRepository,
        Scenes $sceneRepository,
        UrlGenerator $urlGenerator
    ) {
        $this->bookRepository = $bookRepository;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(LatestChanges $query): array
    {
        $timeline = [
            Book::class => $this->mapItemsToView($this->bookRepository->findLatest(), 'book_edit'),
            Chapter::class => $this->mapItemsToView($this->chapterRepository->findLatest(), 'chapter_edit'),
            Scene::class => $this->mapItemsToView($this->sceneRepository->findLatest(), 'scene_edit')
        ];

        return array_filter($timeline, function (array $items): bool {
            return 0 !== count($items);
        });
    }

    private function mapItemsToView(array $items, string $route): array
    {
        $viewItems = array_map(function (array $data) use ($route): array {
            return [
                'date' => new DateTimeImmutable($data['date']),
                'label' => $data['label'],
                'updated' => $data['updated'],
                'url' => $this->urlGenerator->generate($route, ['id' => $data['id']])
            ];
        }, $items);

        uasort($viewItems, function (array $a, array $b): int {
            return $b['date'] <=> $a['date'];
        });

        return $viewItems;
    }
}
