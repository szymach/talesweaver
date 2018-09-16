<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\BookRepository;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

class DashboardController
{
    private const ICONS = [
        Book::class => 'fa-book',
        Chapter::class => 'fa-clipboard',
        Scene::class => 'fa-sticky-note'
    ];

    private const ROUTES = [
        Book::class => 'book_edit',
        Chapter::class => 'chapter_edit',
        Scene::class => 'scene_edit'
    ];

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * @var ChapterRepository
     */
    private $chapterRepository;

    /**
     * @var SceneRepository
     */
    private $sceneRepository;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        BookRepository $bookRepository,
        ChapterRepository $chapterRepository,
        SceneRepository $sceneRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->bookRepository = $bookRepository;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
    }

    public function createView(): ResponseInterface
    {
        $timeline = [];
        $this->addItems($timeline, $this->bookRepository, Book::class);
        $this->addItems($timeline, $this->chapterRepository, Chapter::class);
        $this->addItems($timeline, $this->sceneRepository, Scene::class);

        uasort($timeline, function (array $a, array $b): int {
            return new DateTimeImmutable($b['date']) <=> new DateTimeImmutable($a['date']);
        });

        return $this->responseFactory->fromTemplate('dashboard.html.twig', ['timeline' => $timeline]);
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
