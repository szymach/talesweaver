<?php

namespace AppBundle\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * @author Piotr Szymaszek
 */
class DataContext extends DefaultContext
{
    /** @BeforeScenario */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }
}
