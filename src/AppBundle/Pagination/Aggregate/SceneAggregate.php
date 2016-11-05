<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function getStandalone()
    {
        return $this->standalonePaginator->getResults();
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getCharactersForScene(Scene $scene)
    {
        return $this->characterPaginator->getForSceneResults($scene);
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getItemsForScene(Scene $scene)
    {
        return $this->itemPaginator->getForSceneResults($scene);
    }

    /**
     * @param Scene $scene
     * @return Pagerfanta
     */
    public function getLocationsForScene(Scene $scene)
    {
        return $this->locationPaginator->getForSceneResults($scene);
    }
}
