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
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav navbar-nav')
        ;

        $menu->addChild('menu.start', ['route' => 'app_index']);
        $this->createBookMenu($menu);
        $this->createChapterMenu($menu);
        $this->createSceneMenu($menu);

        return $menu;
    }

    private function createBookMenu(ItemInterface $menu)
    {
        $scenes = $menu->addChild('menu.books.root');
        $scenes->addChild('menu.books.standalone', [
            'route' => 'app_book_list'
        ]);
    }

    private function createChapterMenu(ItemInterface $menu)
    {
        $scenes = $menu->addChild('menu.chapters.root');
        $scenes->addChild('menu.chapters.standalone', [
            'route' => 'app_chapter_list'
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
