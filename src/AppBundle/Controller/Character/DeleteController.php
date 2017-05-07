<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
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
