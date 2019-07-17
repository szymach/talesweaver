<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Twig;

use Exception;
use Talesweaver\Application\Data\Sortable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension
{
    /**
     * @var Sortable
     */
    private $sortable;

    /**
     * @var string
     */
    private $projectDirectory;

    public function __construct(Sortable $sortable, string $projectDirectory)
    {
        $this->sortable = $sortable;
        $this->projectDirectory = $projectDirectory;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'isActiveMenuItem',
                function (string $currentRoute, string $checkedRoute): bool {
                    return $this->isActiveMenuItemFunction($currentRoute, $checkedRoute);
                }
            ),
            new TwigFunction(
                'isActiveSort',
                function (string $list, string $field, string $direction): bool {
                    $current = $this->sortable->createFromSession($list);
                    if (null === $current) {
                        return false;
                    }

                    return $field === $current->getField() && $direction === $current->getDirection();
                }
            ),
            new TwigFunction(
                'fileGetContents',
                function (string $filePath): string {
                    $fullPath = "{$this->projectDirectory}/public/{$filePath}";
                    $contents = file_get_contents($fullPath);
                    if (false === $contents) {
                        throw new Exception("Cannot read file {$fullPath}");
                    }

                    return $contents;
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
