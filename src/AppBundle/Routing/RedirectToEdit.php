<?php

declare(strict_types=1);

namespace AppBundle\Routing;

use Ramsey\Uuid\UuidInterface;
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

    public function createResponse(string $route, UuidInterface $id): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($route, ['id' => $id]));
    }
}
