<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Symfony\Component\Routing\RouterInterface;

final class NavigationModule extends Module
{
    private const LOCALE = 'pl';

    /**
     * @var Symfony
     */
    private $symfony;

    /**
     * phpcs:disable
     */
    public function _before(TestInterface $test)
    {
        $this->symfony = $this->getModule('Symfony');
    }

    public function canSeeIAmOnRouteLocale(
        string $name,
        array $parameters = [],
        string $locale = self::LOCALE
    ): void {
        $url = $this->createUrl($name, $parameters, $locale);
        $this->symfony->amOnPage($url);
        $this->symfony->seeCurrentUrlEquals($url);
        $this->symfony->seeResponseCodeIs(200);
    }

    public function createUrl(string $name, array $parameters = [], string $locale = self::LOCALE): string
    {
        return $this->getRouter()->generate(
            $name,
            array_merge(['_locale' => $locale], $parameters)
        );
    }

    private function getRouter(): RouterInterface
    {
        return $this->symfony->grabService('router');
    }
}
