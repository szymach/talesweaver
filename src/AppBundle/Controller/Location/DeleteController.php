<?php

namespace AppBundle\Controller\Location;

use AppBundle\Entity\Location;
use AppBundle\Entity\Scene;
use AppBundle\Pagination\LocationPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Templating\EngineInterface;

class DeleteController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $manager,
        LocationPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function deleteAction(Scene $scene, Location $location, $page)
    {
        $this->manager->remove($location);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("location", options={"id" = "location_id"})
     */
    public function removeFromSceneAction(Scene $scene, Location $location, $page)
    {
        $scene->removeLocation($location);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
