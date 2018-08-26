<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

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
        $menu = $this->factory->createItem('root')->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('menu.start', ['route' => 'index']);
        $this->createBookMenu($menu);
        $this->createChapterMenu($menu);
        $this->createSceneMenu($menu);

        return $menu;
    }

    private function createBookMenu(ItemInterface $menu): void
    {
        $menu->addChild('menu.books', ['route' => 'book_list']);
    }

    private function createChapterMenu(ItemInterface $menu): void
    {
        $menu->addChild('menu.chapters', ['route' => 'chapter_list']);
    }

    private function createSceneMenu(ItemInterface $menu): void
    {
        $menu->addChild('menu.scenes', ['route' => 'scene_list']);
    }
}
