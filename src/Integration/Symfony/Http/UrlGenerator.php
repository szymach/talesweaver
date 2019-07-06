<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Http;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Talesweaver\Application\Http\UrlGenerator as ApplicationUrlGenerator;

class UrlGenerator implements ApplicationUrlGenerator
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generate(string $route, ?array $parameters = []): string
    {
        return $this->urlGenerator->generate($route, $parameters ?? []);
    }
}
