<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Talesweaver\Application\Http\HtmlContent as ApplicationHtmlContent;
use Twig\Environment;

final class HtmlContent implements ApplicationHtmlContent
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function fromTemplate(string $template, array $parameters)
    {
        return $this->twig->render($template, $parameters);
    }
}
