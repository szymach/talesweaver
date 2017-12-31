<?php

declare(strict_types=1);

namespace App\Templating;

use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class Engine
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function renderResponse(string $template, array $parameters = [], int $status = 200): Response
    {
        return new Response(
            $this->twig->render($template, $parameters),
            $status
        );
    }

    public function render(string $template, array $parameters = []): string
    {
        return $this->twig->render($template, $parameters);
    }
}
