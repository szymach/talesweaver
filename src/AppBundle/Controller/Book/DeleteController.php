<?php

namespace AppBundle\Controller\Book;

use AppBundle\Entity\Book;
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

    public function deleteAction(Book $book, $page)
    {
        $this->manager->remove($book);
        $this->manager->flush();

        return new RedirectResponse(
            $this->router->generate('app_book_list', ['page' => $page])
        );
    }
}
