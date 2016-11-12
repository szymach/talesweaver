<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Entity\Repository\SceneRepository;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Piotr Szymaszek
 */
class DashboardController
{
    /**
     * @var EngineInterface
     */
    private $templating;

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
        ChapterRepository $chapterRepository,
        SceneRepository $sceneRepository
    ) {
        $this->templating = $templating;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
    }

    public function indexAction()
    {
        return $this->templating->renderResponse(
            'base\dashboard.html.twig',
            [
                'standaloneChapters' => $this->chapterRepository->findLatestStandalone(),
                'standaloneScenes' => $this->sceneRepository->findLatestStandalone()
            ]
        );
    }
}
