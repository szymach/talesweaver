<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlertsController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
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
