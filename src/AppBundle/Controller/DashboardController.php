<?php

namespace AppBundle\Controller;

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
     * @var SceneRepository
     */
    private $sceneRepository;

    public function __construct(
        EngineInterface $templating,
        SceneRepository $sceneRepository
    ) {
        $this->templating = $templating;
        $this->sceneRepository = $sceneRepository;
    }

    public function indexAction()
    {
        return $this->templating->renderResponse(
            'base\dashboard.html.twig',
            ['standaloneScenes' => $this->sceneRepository->findLatestStandalone()]
        );
    }
}
