<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\BookRepository;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;
use Talesweaver\Integration\Symfony\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Symfony\Repository\SceneRepository;

class DashboardView
{
    /**
     * @var EngineInterface
     */
    private $templating;

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

    private $icons = [
        Book::class => 'fa-book',
        Chapter::class => 'fa-clipboard',
        Scene::class => 'fa-sticky-note'
    ];

    private $routes = [
        Book::class => 'book_edit',
        Chapter::class => 'chapter_edit',
        Scene::class => 'scene_edit'
    ];

    public function __construct(
        EngineInterface $templating,
        BookRepository $bookRepository,
        ChapterRepository $chapterRepository,
        SceneRepository $sceneRepository
    ) {
        $this->templating = $templating;
        $this->bookRepository = $bookRepository;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
    }

    public function createView(string $locale): Response
    {
        $timeline = [];
        $this->addItems($timeline, $this->bookRepository, Book::class, $locale);
        $this->addItems($timeline, $this->chapterRepository, Chapter::class, $locale);
        $this->addItems($timeline, $this->sceneRepository, Scene::class, $locale);

        uasort($timeline, function (array $a, array $b): int {
            return new DateTimeImmutable($b['date']) <=> new DateTimeImmutable($a['date']);
        });

        return $this->templating->renderResponse('dashboard.html.twig', ['timeline' => $timeline]);
    }

    private function addItems(
        array &$timeline,
        LatestChangesAwareRepository $repository,
        string $class,
        string $locale
    ): void {
        $callback = function (array $timeline, array $item) use ($class): array {
            $timeline[] = array_merge(
                $item,
                ['icon' => $this->icons[$class], 'route' => $this->routes[$class], 'class' => $class]
            );

            return $timeline;
        };

        $timeline = array_reduce($repository->findLatest($locale), $callback, $timeline);
    }
}
