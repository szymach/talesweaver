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
        $timeline = array_merge(
            $this->mapItemsToView($this->bookRepository->findLatest(), Book::class, 'book_edit'),
            $this->mapItemsToView($this->chapterRepository->findLatest(), Chapter::class, 'chapter_edit'),
            $this->mapItemsToView($this->sceneRepository->findLatest(), Scene::class, 'scene_edit')

        );
        uasort($timeline, function (array $a, array $b): int {
            return -($a['date'] <=> $b['date']);
        });

        return $timeline;
    }

    private function mapItemsToView(array $items, string $entity, string $route): array
    {
        return array_map(function (array $data) use ($entity, $route): array {
            return [
                'class' => $entity,
                'date' => new DateTimeImmutable($data['date']),
                'label' => $data['label'],
                'updated' => $data['updated'],
                'url' => $this->urlGenerator->generate($route, ['id' => $data['id']])
            ];
        }, $items);
    }
}
