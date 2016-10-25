<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

/**
 * @author Piotr Szymaszek
 */
class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu()
    {
        $menu = $this->factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav')
        ;

        $menu->addChild('menu.start', ['route' => 'app_index']);

        return $menu;
    }
}
