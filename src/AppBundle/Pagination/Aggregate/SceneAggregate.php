<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Scene\CharacterPaginator;
use AppBundle\Pagination\Scene\ItemPaginator;
use AppBundle\Pagination\Scene\LocationPaginator;
use AppBundle\Pagination\Scene\StandalonePaginator;
use Pagerfanta\Pagerfanta;

class SceneAggregate
{
    /**
     * @var CharacterPaginator
     */
    private $characterPaginator;

    /**
     * @var ItemPaginator
     */
    private $itemPaginator;

    /**
     * @var LocationPaginator
     */
    private $locationPaginator;

    /**
     * @var StandalonePaginator
     */
    private $standalonePaginator;

    public function __construct(
        StandalonePaginator $standalonePaginator,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator
    ) {
        $this->standalonePaginator = $standalonePaginator;
        $this->characterPaginator = $characterPaginator;
        $this->itemPaginator = $itemPaginator;
        $this->locationPaginator = $locationPaginator;
    }

    /**
     * @return Pagerfanta
     */
    public function getStandalone($page)
    {
        return $this->standalonePaginator->getResults($page);
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getCharactersForScene(Scene $scene, $page)
    {
        return $this->characterPaginator->getForSceneResults($scene, $page);
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getItemsForScene(Scene $scene, $page)
    {
        return $this->itemPaginator->getForSceneResults($scene, $page);
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getLocationsForScene(Scene $scene, $page)
    {
        return $this->locationPaginator->getForSceneResults($scene, $page);
    }
}
