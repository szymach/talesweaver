<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Builder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
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
