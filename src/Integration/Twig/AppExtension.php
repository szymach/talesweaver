<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'isActiveMenuItem',
                function (string $currentRoute, string $checkedRoute): bool {
                    return $this->isActiveMenuItemFunction($currentRoute, $checkedRoute);
                }
            )
        ];
    }

    private function isActiveMenuItemFunction(string $currentRoute, string $checkedRoute): bool
    {
        if ('book_list' === $checkedRoute && 0 === strpos($currentRoute, 'book_')) {
            $isActive = true;
        } elseif ('chapter_list' === $checkedRoute && 0 === strpos($currentRoute, 'chapter_')) {
            $isActive = true;
        } elseif ('scene_list' === $checkedRoute && 0 === strpos($currentRoute, 'scene_')) {
            $isActive = true;
        } else {
            $isActive = false;
        }

        return $isActive;
    }
}
