<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\Symfony;

final class ContainerModule extends Module
{
    /**
     * @var Symfony
     */
    private $symfony;

    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->symfony = $moduleContainer->getModule('Symfony');
    }

    public function getService(string $id): object
    {
        return $this->symfony->grabService("test.{$id}");
    }

    public function getParameter(string $name)
    {
        return $this->symfony->_getContainer()->getParameter($name);
    }
}
