<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

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
        $this->createSceneMenu($menu);

        return $menu;
    }

    private function createSceneMenu(ItemInterface $menu)
    {
        $scenes = $menu->addChild('menu.scenes.root');
        $scenes->addChild('menu.scenes.standalone', [
            'route' => 'app_standalone_scene_list'
        ]);
    }
}
