<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\BookRepository;
use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Entity\Repository\SceneRepository;
use Symfony\Component\Templating\EngineInterface;

class DashboardController
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

    public function __invoke()
    {
        return $this->templating->renderResponse(
            'dashboard\index.html.twig',
            [
                'books' => $this->bookRepository->findLatest(),
                'chapters' => $this->chapterRepository->findLatest(),
                'scenes' => $this->sceneRepository->findLatest()
            ]
        );
    }
}
