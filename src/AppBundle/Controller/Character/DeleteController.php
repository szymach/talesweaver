<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Pagination\CharacterPaginator;
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
     * @var CharacterPaginator
     */
    private $pagination;

    public function __construct(
        EngineInterface $templating,
        ObjectManager $manager,
        CharacterPaginator $pagination
    ) {
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function deleteAction(Character $character, Scene $scene, $page)
    {
        $this->manager->remove($character);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function removeFromSceneAction(Scene $scene, Character $character, $page)
    {
        $scene->removeCharacter($character);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->pagination->getForScene($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
