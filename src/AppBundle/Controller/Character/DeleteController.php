<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
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

    public function __construct(EngineInterface $templating, ObjectManager $manager)
    {
        $this->templating = $templating;
        $this->manager = $manager;
    }

    public function deleteAction(Character $character)
    {
        $this->manager->remove($character);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function removeFromSceneAction(Scene $scene, Character $character)
    {
        $scene->removeCharacter($character);
        $this->manager->flush();

        return new JsonResponse(['success' => true]);
    }
}
