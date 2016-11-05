<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Scene;
use AppBundle\Pagination\PaginatorInterface;

/**
 * @author Piotr Szymaszek
 */
interface ForScenePaginatorInterface extends PaginatorInterface
{
    public function getForSceneResults(Scene $scene, $page = 1, $maxPerPage = 10);
}
