<?php

declare(strict_types=1);

namespace App\Routing\Scene;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class EditResponse
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function create(Request $request, UuidInterface $id): Response
    {
        return $request->isXmlHttpRequest()
            ? new JsonResponse([])
            : new RedirectResponse($this->router->generate('app_scene_edit', ['id' => $id]))
        ;
    }
}
