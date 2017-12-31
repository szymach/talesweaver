<?php

declare(strict_types=1);

namespace App\Controller;

use App\Templating\DashboardView;
use Symfony\Component\HttpFoundation\Request;

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
