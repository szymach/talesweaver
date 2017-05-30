<?php

namespace AppBundle\Routing;

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

    public function createResponse(Uuid $id, $route): RedirectResponse
    {
        return new RedirectResponse(
            $this->router->generate($route, ['id' => $id])
        );
    }
}
