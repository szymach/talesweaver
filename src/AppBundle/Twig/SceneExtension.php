<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\SceneAggregate;
use Twig_Extension;
use Twig_SimpleFunction;

class SceneExtension extends Twig_Extension
{
    /**
     * @var SceneAggregate
     */
    private $pagination;

    public function __construct(SceneAggregate $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('sceneCharacters', [$this, 'getSceneCharactersFunction']),
            new Twig_SimpleFunction('sceneItems', [$this, 'getSceneItemsFunction']),
            new Twig_SimpleFunction('sceneLocations', [$this, 'getSceneLocationsFunction']),
            new Twig_SimpleFunction('sceneEvents', [$this, 'getSceneEventsFunction'])
        ];
    }

    public function getSceneCharactersFunction(Scene $scene, $page)
    {
        return $this->pagination->getCharactersForScene($scene, $page);
    }

    public function getSceneItemsFunction(Scene $scene, $page)
    {
        return $this->pagination->getItemsForScene($scene, $page);
    }

    public function getSceneLocationsFunction(Scene $scene, $page)
    {
        return $this->pagination->getLocationsForScene($scene, $page);
    }

    public function getSceneEventsFunction(Scene $scene, $page)
    {
        return $this->pagination->getEventsForScene($scene, $page);
    }
}
