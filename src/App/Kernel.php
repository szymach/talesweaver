<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir()
    {
        return sprintf('%s/var/cache/%s', $this->getProjectDir(), $this->environment);
    }

    public function getLogDir()
    {
        return sprintf('%s/var/log', $this->getProjectDir());
    }

    public function registerBundles()
    {
        $contents = require sprintf('%s/config/bundles.php', $this->getProjectDir());
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->setParameter('container.autowiring.strict_mode', true);
        $container->setParameter('container.dumper.inline_class_loader', true);
        $configDir = $this->getConfigDirectory();
        $loader->load(sprintf('%s/packages/*%s', $configDir, self::CONFIG_EXTS), 'glob');
        if (true === is_dir($configDir.'/packages/'.$this->environment)) {
            $loader->load(
                sprintf('%s/packages/%s/**/*%s', $configDir, $this->environment, self::CONFIG_EXTS),
                'glob'
            );
        }
        $loader->load(sprintf('%s/services%s', $configDir, self::CONFIG_EXTS), 'glob');
        $loader->load(sprintf('%s/services_%s%s', $configDir, $this->environment, self::CONFIG_EXTS), 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $configDir = $this->getConfigDirectory();
        if (true === is_dir(sprintf('%s/routes/', $configDir))) {
            $routes->import(sprintf('%s/routes/*%s', $configDir, self::CONFIG_EXTS), '/', 'glob');
        }
        if (true === is_dir(sprintf('%s/routes/%s', $configDir, $this->environment))) {
            $routes->import(
                sprintf('%s/routes/%s/**/*%s', $configDir, $this->environment, self::CONFIG_EXTS),
                '/',
                'glob'
            );
        }
        $routes->import(sprintf('%s/routes%s', $configDir, self::CONFIG_EXTS), '/', 'glob');
    }

    private function getConfigDirectory(): string
    {
        return sprintf('%s/config', $this->getProjectDir());
    }
}
