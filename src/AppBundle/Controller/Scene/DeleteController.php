<?php

namespace AppBundle\Controller\Scene;

use AppBundle\Entity\Scene;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class DeleteController
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ObjectManager $manager, RouterInterface $router)
    {
        $this->manager = $manager;
        $this->router = $router;
    }

    public function deleteAction(Scene $scene, $page)
    {
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId() : null;
        $this->manager->remove($scene);
        $this->manager->flush();

        return new RedirectResponse(
            $chapterId
            ? $this->router->generate('app_chapter_edit', ['id' => $chapterId])
            : $this->router->generate('app_scene_list', ['page' => $page])
        );
    }
}
