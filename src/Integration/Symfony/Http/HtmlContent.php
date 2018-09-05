<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Symfony\Component\Templating\EngineInterface;
use Talesweaver\Application\Http\HtmlContent as ApplicationHtmlContent;

class HtmlContent implements ApplicationHtmlContent
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function fromTemplate(string $template, array $parameters)
    {
        return $this->templating->render($template, $parameters);
    }
}
