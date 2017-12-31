<?php

declare(strict_types=1);

namespace App\Controller;

use App\Templating\Engine;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlertsController
{
    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke()
    {
        return new JsonResponse([
            'alerts' => $this->templating->render('partial/alerts.html.twig')
        ]);
    }
}
