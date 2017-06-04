<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\Character\CharacterPaginator;
use AppBundle\Pagination\EventPaginator;
use AppBundle\Pagination\Item\ItemPaginator;
use AppBundle\Pagination\Location\LocationPaginator;
use AppBundle\Pagination\Scene\ScenePaginator;
use Pagerfanta\Pagerfanta;

class SceneAggregate
{
    /**
     * @var ScenePaginator
     */
    private $scenePaginator;

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
     * @var EventPaginator
     */
    private $eventPaginator;

    public function __construct(
        ScenePaginator $scenePaginator,
        CharacterPaginator $characterPaginator,
        ItemPaginator $itemPaginator,
        LocationPaginator $locationPaginator,
        EventPaginator $eventPaginator
    ) {
        $this->scenePaginator = $scenePaginator;
        $this->characterPaginator = $characterPaginator;
        $this->itemPaginator = $itemPaginator;
        $this->locationPaginator = $locationPaginator;
        $this->eventPaginator = $eventPaginator;
    }

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function getStandalone(int $page)
    {
        return $this->scenePaginator->getResults($page);
    }

    /**
     * @param Scene $scene
     * @param int $page
     * @return Pagerfanta
     */
    public function getCharactersForScene(Scene $scene, int $page)
    {
        return $this->characterPaginator->getResults($scene, $page);
    }

    /**
     * @param Scene $scene
     * @param int $page
     * @return Pagerfanta
     */
    public function getItemsForScene(Scene $scene, int $page)
    {
        return $this->itemPaginator->getResults($scene, $page);
    }

    /**
     * @param Scene $scene
     * @param int $page
     * @return Pagerfanta
     */
    public function getLocationsForScene(Scene $scene, int $page)
    {
        return $this->locationPaginator->getResults($scene, $page);
    }

    /**
     * @param Scene $scene
     * @param int $page
     * @return Pagerfanta
     */
    public function getEventsForScene(Scene $scene, int $page)
    {
        return $this->eventPaginator->getResults($scene, $page);
    }
}
