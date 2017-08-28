<?php

namespace AppBundle\Templating;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Entity\Repository\BookRepository;
use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Entity\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Entity\Repository\SceneRepository;
use AppBundle\Entity\Scene;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

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
        Book::class => 'app_book_edit',
        Chapter::class => 'app_chapter_edit',
        Scene::class => 'app_scene_edit'
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

    public function createView(string $locale) : Response
    {
        $timeline = [];
        $this->addItems($timeline, $this->bookRepository, Book::class, $locale);
        $this->addItems($timeline, $this->chapterRepository, Chapter::class, $locale);
        $this->addItems($timeline, $this->sceneRepository, Scene::class, $locale);

        uasort($timeline, function (array $itemA, array $itemB) : int {
            $a = new DateTimeImmutable($itemA['date']);
            $b = new DateTimeImmutable($itemB['date']);
            if ($a == $b) {
                return 0;
            }

            return ($a > $b) ? -1 : 1;
        });

        return $this->templating->renderResponse(
            'dashboard.html.twig',
            ['timeline' => $timeline]
        );
    }

    private function addItems(
        array &$timeline,
        LatestChangesAwareRepository $repository,
        string $class,
        string $locale
    ) : void {
        foreach ($repository->findLatest($locale) as $item) {
            $timeline[] = array_merge(
                $item,
                [
                    'icon' => $this->icons[$class],
                    'route' => $this->routes[$class],
                    'class' => $class
                ]
            );
        }
    }
}
