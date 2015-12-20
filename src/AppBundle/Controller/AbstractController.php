<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;

use AppBundle\Element\Interfaces\ElementInterface;
use AppBundle\Element\Manager\Manager;

/**
 * @author Piotr Szymaszek
 */
abstract class AbstractController
{
    /**
     * @var Manager
     */
    protected $elementManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @param string $name
     * @return ElementInterface
     */
    protected function getElement($name)
    {
        return $this->elementManager->getElement($name);
    }
}
