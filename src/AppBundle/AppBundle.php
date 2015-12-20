<?php

namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use AppBundle\DependencyInjection\Compiler\ElementCompilerPass;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ElementCompilerPass());
    }
}
