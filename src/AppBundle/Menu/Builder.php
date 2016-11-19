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
        $this->createChapterMenu($menu);
        $this->createSceneMenu($menu);

        return $menu;
    }

    private function createChapterMenu(ItemInterface $menu)
    {
        $scenes = $menu->addChild('menu.chapters.root');
        $scenes->addChild('menu.chapters.standalone', [
            'route' => 'app_standalone_chapter_list'
        ]);
    }

    private function createSceneMenu(ItemInterface $menu)
    {
        $scenes = $menu->addChild('menu.scenes.root');
        $scenes->addChild('menu.scenes.standalone', [
            'route' => 'app_scene_list'
        ]);
    }
}
