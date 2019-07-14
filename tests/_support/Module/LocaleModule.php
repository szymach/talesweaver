<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Talesweaver\Tests\Module\ContainerModule;

final class LocaleModule extends Module
{
    /**
     * phpcs:disable
     */
    public function _initialize()
    {
        /** @var ContainerModule $containerModule */
        $containerModule = $this->getModule(ContainerModule::class);

        /** @var TranslatableListener $translatableListener */
        $translatableListener = $containerModule->getService('fsi_doctrine_extensions.listener.translatable');
        $translatableListener->setLocale('pl');
    }
}
