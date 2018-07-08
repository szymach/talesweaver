<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Routing;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectToList
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function createResponse(string $route, int $page): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($route, ['page' => $page]));
    }
}
