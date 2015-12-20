<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Piotr Szymaszek
 */
class ElementCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        if (!$this->container->has('app.element.manager')) {
            return;
        }

        $managerDefinition = $container->findDefinition('app.element.manager');
        $elements = $container->findTaggedServiceIds('app.element');
        foreach (array_keys($elements) as $id) {
            $managerDefinition->addMethodCall('addElement', [new Reference($id)]);
        }
    }
}
