<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Talesweaver\Tests\Module\ContainerModule;

class LocaleModule extends Module
{
    /**
     * @var ContainerModule
     */
    private $containerModule;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->containerModule = $moduleContainer->getModule(ContainerModule::class);
    }

    /**
     * phpcs:disable
     */
    public function _initialize()
    {
        $this->getTranslatableListener()->setLocale('pl');
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->containerModule->getService('fsi_doctrine_extensions.listener.translatable');
    }
}
