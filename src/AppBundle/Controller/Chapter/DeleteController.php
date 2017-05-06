<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
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

    public function deleteAction(Chapter $chapter, $page)
    {
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId() : null;
        $this->manager->remove($chapter);
        $this->manager->flush();

        return new RedirectResponse(
            $bookId
            ? $this->router->generate('app_book_edit', ['id' => $bookId])
            : $this->router->generate('app_chapter_list', ['page' => $page])
        );
    }
}
