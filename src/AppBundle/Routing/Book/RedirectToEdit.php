<?php

namespace AppBundle\Routing\Book;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectToEdit
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function createResponse(Uuid $id): RedirectResponse
    {
        return new RedirectResponse(
            $this->router->generate('app_book_edit', ['id' => $id])
        );
    }
}
