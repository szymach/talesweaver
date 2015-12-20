<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

use AppBundle\Element\Manager\Manager;

/**
 * @author Piotr Szymaszek
 */
class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var Manager
     */
    private $elementManager;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(
        FactoryInterface $factory,
        Manager $elementManager
    ) {
        $this->factory = $factory;
        $this->elementManager = $elementManager;
    }

    /**
     * @return ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');

        foreach ($this->elementManager->getElements() as $element) {
            $name = $element->getId();
            $menu->addChild(
                sprintf('front.menu.%s', $name),
                [
                    'route' => 'crud_list',
                    'routeParameters' => ['elementName' => $name]
                ]
            );
        }

        return $menu;
    }
}
