<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Talesweaver\Integration\Symfony\Templating\DashboardView;

class DashboardController
{
    /**
     * @var DashboardView
     */
    private $view;

    public function __construct(DashboardView $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request)
    {
        return $this->view->createView($request->getLocale());
    }
}
